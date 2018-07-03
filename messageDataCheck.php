<?php

function getMessageDataErrorsNum()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT MessageId as EntityId FROM ProtonMailShard.MessageData 
                                                WHERE Body not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                UNION
                                                SELECT MessageId as EntityId FROM ProtonMailShard.MessageData 
                                                WHERE Header not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)";
    $result = $conn->query($sql);
    $messageDataErrorsNum = $result->num_rows;
    $conn->close();
    return $messageDataErrorsNum;
}


function getMessageErrors()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $messageErrorsQuery = "SELECT MessageId as EntityId, 'Body Data' as EntityDesc, Body as MissingBlobId FROM ProtonMailShard.MessageData
                                WHERE Body not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT MessageId as EntityId, 'Header Data' as EntityDesc, Header as MissingBlobId FROM ProtonMailShard.MessageData
                                WHERE Header not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($messageErrorsQuery);
    $conn->close();
    return $result;
}

?>