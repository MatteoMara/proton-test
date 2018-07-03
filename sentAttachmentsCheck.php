<?php


function getSentAttachmentErrorsNum(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT SentAttachmentId as total FROM ProtonMailGlobal.SentAttachment 
                                              WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)";
    $result = $conn->query($sql);
    $sentAttachmentsErrorsNum = $result->num_rows;
    $conn->close();
    return $sentAttachmentsErrorsNum;
}


function getSentAttachmentErrors(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sentAttachmentsErrorsQuery = "SELECT SentAttachmentId as EntityId, 'Sent Attachment' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailGlobal.SentAttachment 
                                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($sentAttachmentsErrorsQuery);
    $conn->close();
    return $result;
}

?>