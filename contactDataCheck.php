<?php

function getContactDataErrorsNum()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $contactDataIdQuery = "SELECT ContactDataId 
        FROM ProtonMailShard.ContactData 
        WHERE BlobStorageID not in (SELECT BlobStorageID from ProtonMailGlobal.BlobStorage)";
    $contactDataId = $conn->query($contactDataIdQuery);

    $contactDataIdNum = $contactDataId->num_rows;
    $conn->close();
    return $contactDataIdNum;
}


function getContactDataErrors()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $contactDataErrorsQuery = "SELECT ContactDataId as EntityId, 'Contact Data' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailShard.ContactData 
                                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                                ORDER BY EntityDesc, MissingBlobId, EntityId";
    $result = $conn->query($contactDataErrorsQuery);
    $conn->close();
    return $result;
}

?>