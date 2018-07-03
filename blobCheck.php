<?php


function getBlobErrorsNum()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $limit = calculateRowsLimit();
    $offset = calculateOffset($limit);

    $blobErrorsQuery = "Select BlobStorageId from 
                                                (Select BlobStorageId, NumReferences from ProtonMailGlobal.BlobStorage ORDER BY BlobStorageId LIMIT " . $limit . " OFFSET " . $offset . ") as storage 
                                                LEFT JOIN
                                                (Select BlobId, SUM(Occurrencies) as RealCount from (
                                                SELECT 'Body' as Type, Body as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.MessageData
                                                GROUP BY Body
                                                UNION
                                                SELECT 'Header' as Type, Header as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.MessageData
                                                GROUP BY Header
                                                UNION
                                                SELECT 'Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.Attachment
                                                GROUP BY BlobStorageId
                                                UNION
                                                SELECT 'Outside Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.OutsideAttachment
                                                GROUP BY BlobStorageId
                                                UNION
                                                SELECT 'Contact Data' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.ContactData
                                                GROUP BY BlobStorageId
                                                UNION
                                                SELECT 'Sent Message' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailGlobal.SentMessage
                                                GROUP BY BlobStorageId
                                                UNION
                                                SELECT 'Sent Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailGlobal.SentAttachment
                                                GROUP BY BlobStorageId
                                                ) as BlobCounts
                                                GROUP BY BlobId) blobCheck
                                                on storage.BlobStorageID = BlobId  
                                                WHERE storage.NumReferences != RealCount OR RealCount is null";
    $blobErrors = $conn->query($blobErrorsQuery);
    $blobErrorsNum = $blobErrors->num_rows;
    $conn->close();
    return $blobErrorsNum;
}

function getItemsWithMissingBlob()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $missingBlobQuery = "SELECT MessageId as EntityId, 'Body Data' as EntityDesc, Body as MissingBlobId FROM ProtonMailShard.MessageData
                                WHERE Body not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT MessageId as EntityId, 'Header Data' as EntityDesc, Header as MissingBlobId FROM ProtonMailShard.MessageData
                                WHERE Header not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT AttachmentId as EntityId, 'Attachment' as EntityDesc, BlobStorageID as MissingBlobId FROM ProtonMailShard.Attachment
                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT AttachmentId as EntityId, 'Outside Attachment' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailShard.OutsideAttachment
                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT SentAttachmentId as EntityId, 'Sent Attachment' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailGlobal.SentAttachment
                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT SentMessageId as EntityId, 'Sent Message' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailGlobal.SentMessage
                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                UNION
                                SELECT ContactDataId as EntityId, 'Contact Data' as EntityDesc, BlobStorageID as MissingBlobId  FROM ProtonMailShard.ContactData
                                WHERE BlobStorageID not in (SELECT B.BlobStorageID from ProtonMailGlobal.BlobStorage B)
                                ORDER BY EntityDesc, MissingBlobId, EntityId";


    $missingBlob = $conn->query($missingBlobQuery);
    $conn->close();
    return $missingBlob;
}

function getBlobsWithWrongReferences()
{
    // Create connection
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $limit = calculateRowsLimit();
    $offset = calculateOffset($limit);

    $blobReferenceErrorsQuery = "Select BlobStorageId, NumReferences, IFNULL(RealCount, 0) RealCount from 
                                    (Select BlobStorageId, NumReferences from ProtonMailGlobal.BlobStorage ORDER BY BlobStorageId LIMIT " . $limit . " OFFSET " . $offset . ") as storage 
                                    LEFT JOIN
                                    (Select BlobId, SUM(Occurrencies) as RealCount from (
                                    SELECT 'Body' as Type, Body as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.MessageData
                                    GROUP BY Body
                                    UNION
                                    SELECT 'Header' as Type, Header as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.MessageData
                                    GROUP BY Header
                                    UNION
                                    SELECT 'Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.Attachment
                                    GROUP BY BlobStorageId
                                    UNION
                                    SELECT 'Outside Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.OutsideAttachment
                                    GROUP BY BlobStorageId
                                    UNION
                                    SELECT 'Contact Data' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailShard.ContactData
                                    GROUP BY BlobStorageId
                                    UNION
                                    SELECT 'Sent Message' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailGlobal.SentMessage
                                    GROUP BY BlobStorageId
                                    UNION
                                    SELECT 'Sent Attachment' as Type, BlobStorageId as BlobId, COUNT(*) as Occurrencies from ProtonMailGlobal.SentAttachment
                                    GROUP BY BlobStorageId
                                    ) as BlobCounts
                                    GROUP BY BlobId) blobCheck
                                    on BlobStorageID = BlobId
                                    WHERE NumReferences != RealCount OR RealCount is null
                                    ORDER BY `BlobStorageId` ASC";
    $blobReferenceErrors = $conn->query($blobReferenceErrorsQuery);
    $conn->close();
    return $blobReferenceErrors;
}

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

function calculateRowsLimit()
{
    include 'settings.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $countBlobsQuery = "Select COUNT(BlobStorageId) as total from ProtonMailGlobal.BlobStorage
                                 ORDER BY `BlobStorageId` ASC";
    $countBlobs = $conn->query($countBlobsQuery);
    $conn->close();
    $row = mysqli_fetch_assoc($countBlobs);
    return (int)($row['total'] / $totalNodes);
}

function calculateOffset($limit)
{
    include 'settings.php';

    if ($actualNode > $totalNodes) {
        echo "Invalid Node: Node " . $actualNode . " out of " . $totalNodes;
        return $totalNodes;
    }

    return ($actualNode - 1) * $limit;
}

?>