<?php

function getBlobDetails($blobId)
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $blobDetailQuery = "Select BlobStorageId, Version, Type, NumReferences, Size, ShardId from ProtonMailGlobal.BlobStorage
                                    WHERE BlobStorageID = " . $blobId;
    $blobDetail = $conn->query($blobDetailQuery);
    $conn->close();
    return $blobDetail;
}

function getBlobReferences($blobId)
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $blobReferencesQuery = "SELECT MessageId as EntityId, 'Message Body Data' as EntityDesc, Body as BlobId FROM ProtonMailShard.MessageData
                                WHERE Body = " . $blobId . "
                                UNION
                                SELECT MessageId as EntityId, 'Message Header Data' as EntityDesc, Header as BlobId FROM ProtonMailShard.MessageData
                                WHERE Header = " . $blobId . "
                                UNION
                                SELECT AttachmentId as EntityId, 'Attachment' as EntityDesc, BlobStorageID as BlobId FROM ProtonMailShard.Attachment
                                WHERE BlobStorageID = " . $blobId . "
                                UNION
                                SELECT AttachmentId as EntityId, 'Outside Attachment' as EntityDesc, BlobStorageID as BlobId  FROM ProtonMailShard.OutsideAttachment
                                WHERE BlobStorageID = " . $blobId . "
                                UNION
                                SELECT SentAttachmentId as EntityId, 'Sent Attachment' as EntityDesc, BlobStorageID as BlobId  FROM ProtonMailGlobal.SentAttachment
                                WHERE BlobStorageID = " . $blobId . "
                                UNION
                                SELECT SentMessageId as EntityId, 'Sent Message' as EntityDesc, BlobStorageID as BlobId  FROM ProtonMailGlobal.SentMessage
                                WHERE BlobStorageID = " . $blobId . "
                                UNION
                                SELECT ContactDataId as EntityId, 'Contact Data' as EntityDesc, BlobStorageID as BlobId  FROM ProtonMailShard.ContactData
                                WHERE BlobStorageID = " . $blobId . "
                                ORDER BY EntityDesc, BlobId, EntityId";
    $blobReferences = $conn->query($blobReferencesQuery);
    $conn->close();
    return $blobReferences;
}

?>