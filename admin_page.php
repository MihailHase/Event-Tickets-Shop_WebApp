<?php

global $conn;
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

$message = array();

if (isset($_POST['add_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['titlu']);
    $description = mysqli_real_escape_string($conn, $_POST['descriere']);
    $date = $_POST['data'];
    $location = mysqli_real_escape_string($conn, $_POST['locatie']);
    $speaker = mysqli_real_escape_string($conn, $_POST['speaker']);
    $partners = mysqli_real_escape_string($conn, $_POST['parteneri']);
    $sponsors = mysqli_real_escape_string($conn, $_POST['sponsori']);
    $price = mysqli_real_escape_string($conn, $_POST['pret']);

    $select_event_title = mysqli_query($conn, "SELECT titlu FROM `evenimente` WHERE titlu = '$title'") or die('query failed');

    if (mysqli_num_rows($select_event_title) > 0) {
        $message[] = 'Event title already added';
    } else {
        $add_event_query = mysqli_query($conn, "INSERT INTO `evenimente` (titlu, descriere, data, locatie, speaker, parteneri, sponsori, pret) VALUES ('$title', '$description', '$date', '$location', '$speaker', '$partners', '$sponsors', '$price')") or die('query failed');

        if ($add_event_query) {
            $message[] = 'Event added successfully!';
        } else {
            $message[] = 'Event could not be added!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_event_query = mysqli_query($conn, "SELECT titlu FROM `evenimente` WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_event = mysqli_fetch_assoc($delete_event_query);

    $event_title = $fetch_delete_event['titlu'];

    mysqli_query($conn, "DELETE FROM `evenimente` WHERE id = '$delete_id'") or die('query failed');

    if ($delete_event_query) {
        $message[] = 'Event deleted successfully!';
    } else {
        $message[] = 'Event could not be deleted!';
    }
}

if (isset($_POST['update_event'])) {
    $update_id = $_POST['update_id'];
    $update_title = mysqli_real_escape_string($conn, $_POST['update_title']);
    $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);
    $update_date = $_POST['update_date'];
    $update_location = mysqli_real_escape_string($conn, $_POST['update_location']);
    $update_speaker = mysqli_real_escape_string($conn, $_POST['update_speaker']);
    $update_partners = mysqli_real_escape_string($conn, $_POST['update_partners']);
    $update_sponsors = mysqli_real_escape_string($conn, $_POST['update_sponsors']);
    $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);

    $update_event_query = mysqli_query($conn, "UPDATE `evenimente` SET titlu = '$update_title', descriere = '$update_description', data = '$update_date', locatie = '$update_location', speaker = '$update_speaker', parteneri = '$update_partners', sponsori = '$update_sponsors', pret = '$update_price' WHERE id = '$update_id'") or die('query failed');

    if ($update_event_query) {
        $message[] = 'Event edited successfully!';
    } else {
        $message[] = 'Event could not be edited!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ADMIN PANEL</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body class="w3-main">

<header class="w3-container">
    <p class="w3-left w3-xlarge">ADMIN CONTROL PANEL</p>
    <p class="w3-right">
        <a href="home.php"><i class="fa fa-home w3-margin-right w3-xxlarge"></i></a>
        <a href="logout.php"><i class="fa fa-sign-out w3-margin-right w3-xxlarge"></i></a>
    </p>
    <p class="w3-center"><strong>Admin username: </strong><?php echo $_SESSION['user_name']; ?></p>
    <p class="w3-center"><strong>Admin email: </strong><?php echo $_SESSION['admin_email']; ?></p>
</header>

<div class="w3-container">
    <form action="" method="post">
        <h3 class="event-title">Add event</h3>
        <input type="text" name="titlu" class="box form-control" placeholder="Enter title name" required>
        <input type="text" name="descriere" class="box form-control" placeholder="Enter description" required>
        <input type="date" name="data" class="box form-control" placeholder="Enter the date" required>
        <input type="text" name="locatie" class="box form-control" placeholder="Enter event location" required>
        <input type="text" name="speaker" class="box form-control" placeholder="Enter speaker name" required>
        <input type="text" name="parteneri" class="box form-control" placeholder="Enter partner name" required>
        <input type="text" name="sponsori" class="box form-control" placeholder="Enter sponsors name" required>
        <input type="number" min="0" name="pret" class="box form-control" placeholder="Enter ticket price" required>
        <input type="submit" value="Add event" name="add_event" class="w3-button w3-black">
    </form>
</div>

<div>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
        }
    }
    ?>
</div>

<div class="w3-row-padding">
    <?php
    $select_events = mysqli_query($conn, "SELECT * FROM `evenimente`") or die('query failed');
    if (mysqli_num_rows($select_events) > 0) {
        $count = 0;
        while ($fetch_events = mysqli_fetch_assoc($select_events)) {
            if ($count % 4 == 0) {
                echo '<div class="w3-row">';
            }
            ?>
            <div class="w3-col s3">
                <div class="box w3-container">
                    <div class="title event-title"><?php echo $fetch_events['titlu']; ?></div>
                    <div class="description event-details"><?php echo $fetch_events['descriere']; ?></div>
                    <div class="date event-details"><?php echo $fetch_events['data']; ?></div>
                    <div class="location event-details"><?php echo $fetch_events['locatie']; ?></div>
                    <div class="speaker event-details"><?php echo $fetch_events['speaker']; ?></div>
                    <div class="partners event-details"><?php echo $fetch_events['parteneri']; ?></div>
                    <div class="sponsors event-details"><?php echo $fetch_events['sponsori']; ?></div>
                    <div class="price event-price">$<?php echo $fetch_events['pret']; ?></div>
                    <a href="admin_page.php?update=<?php echo $fetch_events['id']; ?>"
                       class="w3-button w3-black">update</a>
                    <a href="admin_page.php?delete=<?php echo $fetch_events['id']; ?>" class="w3-button w3-black"
                       onclick="return confirm('Delete this event?');">delete</a>
                </div>
            </div>
            <?php
            $count++;
            if ($count % 4 == 0) {
                echo '</div>';
            }
        }
        if ($count % 4 != 0) {
            echo '</div>';
        }
    } else {
        echo '<p class="empty">No events added yet!</p>';
    }
    ?>
</div>

<div class="w3-container upd">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `evenimente` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                ?>
                <form action="" method="post">
                    <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
                    <input type="text" name="update_title" value="<?php echo $fetch_update['titlu']; ?>"
                           class="box form-control" required placeholder="Enter event title">
                    <textarea name="update_description" class="box form-control" required
                              placeholder="Enter event description"><?php echo $fetch_update['descriere']; ?></textarea>
                    <input type="date" name="update_date" value="<?php echo $fetch_update['data']; ?>"
                           class="box form-control" required placeholder="Select event date">
                    <input type="text" name="update_location" value="<?php echo $fetch_update['locatie']; ?>"
                           class="box form-control" required placeholder="Enter event location">
                    <input type="text" name="update_speaker" value="<?php echo $fetch_update['speaker']; ?>"
                           class="box form-control" required placeholder="Enter event speaker">
                    <input type="text" name="update_partners" value="<?php echo $fetch_update['parteneri']; ?>"
                           class="box form-control" placeholder="Enter event partners">
                    <input type="text" name="update_sponsors" value="<?php echo $fetch_update['sponsori']; ?>"
                           class="box form-control" placeholder="Enter event sponsors">
                    <input type="number" name="update_price" value="<?php echo $fetch_update['pret']; ?>" min="0"
                           class="box form-control" required placeholder="Enter event price">
                    <input type="submit" value="Update" name="update_event" class="w3-button w3-black">
                    <input type="reset" value="Cancel" id="close-update" class="w3-button w3-black">
                </form>
                <?php
            }
        }
    }
    ?>
</div>

<div class="w3-black w3-center w3-padding-24">Powered by
    <a href="#" title="#" target="_blank" class="w3-hover-opacity">Mihail</a>
</div>

<script>
    document.getElementById('close-update').addEventListener('click', function () {
        document.querySelector('.upd').style.display = 'none';
    });
    document.getElementById('close-update').addEventListener('click', function () {
        window.close();
    });
</script>

</body>
</html>
