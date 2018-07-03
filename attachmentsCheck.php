<?php
function getAttachmentErrorsNum(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT AttachmentId FROM ProtonMailShard.Attachment 
                                              WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)";
    $result = $conn->query($sql);
    $attachmentsErrorsNum = $result->num_rows;
    $conn->close();
    return $attachmentsErrorsNum;
}


function getAttachmentErrors(){
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $attachmentsErrorsQuery = "SELECT AttachmentId as EntityId, 'Attachment' as EntityDesc, BlobStorageID as MissingBlobId FROM ProtonMailShard.Attachment 
                                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($attachmentsErrorsQuery);
    $conn->close();
    return $result;
}



?>