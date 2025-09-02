<?php
// controllers/offers-controller.php

// <== Include Required Files ==>
include('../db_connect.php');
include('../helpers/image-upload.php');
include('../models/CRUD.php');
include('../models/Offer.php');

// <== Initialize Models ==>
$MODEL = new CRUD($pdo);
$OFFER = new Offer($pdo);

// <== Table Names ==>
$offersTable = 'offers';
$offerProductsJunctionTable = 'offer_products_junction';

// <== Get Action From Request ==>
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// <== Handle Action Switch ==>
switch ($action) {

    // --------------------------------------
    // <== CREATE OFFER ==>
    // --------------------------------------
    case 'create':
        $offerName       = $_POST['offer_name'] ?? "";
        $description     = $_POST['offer_description'] ?? '';
        $offerProducts   = $_POST['offer_products'] ?? [];
        $discountPercent = $_POST['offer_discount_per'] ?? 0;
        $validUntil      = $_POST['offer_end_date'] ?? "";

        // <== Validate Required Fields ==>
        if (!$offerName || !$discountPercent || !$validUntil) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }

        // <== Check for Duplicate Offer Name ==>
        if ($MODEL->checkDuplicate('offer_name', $offerName, $offersTable)) {
            echo json_encode(['success' => false, 'message' => 'Offer already exists']);
            exit;
        }

        // <== Prepare Offer Data ==>
        $data = [
            'offer_name'        => $offerName,
            'description'       => $description,
            'discount_percent'  => $discountPercent,
            'valid_until'       => $validUntil,
        ];

        // <== Insert Offer ==>
        $offerId = $MODEL->create($data, $offersTable);

        if (!$offerId) {
            echo json_encode(['success' => false, 'message' => 'Failed to create offer']);
            exit;
        }

        // <== Insert Product Links ==>
        $skippedProducts = [];

        foreach ($offerProducts as $productId) {
            $productId = (int) $productId;

            try {
                $result = $MODEL->create([
                    'offer_id'   => $offerId,
                    'product_id' => $productId
                ], $offerProductsJunctionTable);

                if ($result === false) {
                    $skippedProducts[] = $productId;
                }
            } catch (Exception $e) {
                $skippedProducts[] = $productId;
            }
        }

        // <== Build Response Message ==>
        $message = "Offer created successfully.";
        if (!empty($skippedProducts)) {
            $message .= " However, the following product(s) were already linked and skipped: " . implode(', ', $skippedProducts) . ".";
        }

        echo json_encode([
            'success' => true,
            'message' => $message
        ]);
        break;

    // --------------------------------------
    // <== UPDATE OFFER ==>
    // --------------------------------------
    case 'update':
        $id = $_POST['offer_id'] ?? null;
        $offerName = $_POST['offer_name'] ?? "";
        $description = $_POST['offer_description'] ?? '';
        $offerProducts = $_POST['offer_products'] ?? [];
        $discountPercent = $_POST['offer_discount_per'] ?? 0;
        $validUntil = $_POST['offer_end_date'] ?? "";

        // <== Validate ID ==>
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing offer ID']);
            exit;
        }

        // <== Check if Offer Exists ==>
        $existing = $MODEL->getById('id', $id, $offersTable);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Offer not found']);
            exit;
        }

        // <== Prepare Data for Update ==>
        $data = [
            'offer_name'        => $offerName,
            'description'       => $description,
            'discount_percent'  => $discountPercent,
            'valid_until'       => $validUntil,
        ];

        // <== Update Offer ==>
        $success = $MODEL->update($data, 'id', $id, $offersTable);

        if (!$success) {
            echo json_encode(['success' => false, 'message' => 'Failed to update offer']);
            exit;
        }

        // <== Delete Existing Product Links ==>
        $pdo->prepare("DELETE FROM $offerProductsJunctionTable WHERE offer_id = ?")->execute([$id]);

        // <== Re-insert Updated Product Links ==>
        $skippedProducts = [];

        foreach ($offerProducts as $productId) {
            $productId = (int) $productId;

            try {
                $result = $MODEL->create([
                    'offer_id'   => $id,
                    'product_id' => $productId
                ], $offerProductsJunctionTable);

                if ($result === false) {
                    $skippedProducts[] = $productId;
                }
            } catch (Exception $e) {
                $skippedProducts[] = $productId;
                continue;
            }
        }

        // <== Build Response Message ==>
        $message = "Offer updated successfully.";
        if (!empty($skippedProducts)) {
            $message .= " However, the following product(s) were skipped: " . implode(', ', $skippedProducts) . ".";
        }

        echo json_encode(['success' => true, 'message' => $message]);
        break;
    
    // --------------------------------------
    // <== DELETE OFFER ==>
    // --------------------------------------
    case 'delete':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // <== Delete Offer ==>
        $success = $MODEL->delete('id', $id, $offersTable);
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== FETCH ALL OFFERS ==>
    // --------------------------------------
    case 'fetch':
        $offers = $MODEL->getAll($offersTable);
        $products = $OFFER->getProductsForSelectInput();

        // <== Append Related Product IDs to Each Offer ==>
        foreach ($offers as &$offer) {
            $stmt = $pdo->prepare("SELECT product_id FROM $offerProductsJunctionTable WHERE offer_id = ?");
            $stmt->execute([$offer['id']]);
            $productIds = $stmt->fetchAll(PDO::FETCH_COLUMN); // Returns array of product_ids

            $offer['product_ids'] = array_map('intval', $productIds); // Convert to integers
        }

        // <== Respond with Offers and Product List ==>
        echo json_encode([
            'success' => true,
            'data' => $offers,
            'products' => $products
        ]);
        break;

    // --------------------------------------
    // <== DEFAULT: INVALID ACTION ==>
    // --------------------------------------
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
