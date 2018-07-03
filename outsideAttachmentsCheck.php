<?php

function getOutsideAttachmentErrorsNum(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT AttachmentId FROM ProtonMailShard.OutsideAttachment 
                                              WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)";
    $result = $conn->query($sql);
    $outsideAttachmentsErrorsNum = $result->num_rows;
    $conn->close();
    return $outsideAttachmentsErrorsNum;
}


function getOutsideAttachmentErrors(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $outsideAttachmentsErrorsQuery = "SELECT AttachmentId as EntityId, 'Outside Attachment' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailShard.OutsideAttachment 
                                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($outsideAttachmentsErrorsQuery);
    $conn->close();
    return $result;
}

?>