<?php

function getSentMessageErrorsNum()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT SentMessageId FROM ProtonMailGlobal.SentMessage
                                              WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)";
    $result = $conn->query($sql);
    $sentMessageErrorsNum = $result->num_rows;
    $conn->close();
    return $sentMessageErrorsNum;
}


function getSentMessageErrors()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sentMessageErrorsQuery = "SELECT SentMessageId as EntityId, 'Sent Message' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailGlobal.SentMessage
                                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($sentMessageErrorsQuery);
    $conn->close();
    return $result;
}

?>