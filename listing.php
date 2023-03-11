<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
    $start_date = date("Y-m-d", strtotime('now'));
    $end_date = date("Y-m-d", strtotime('tomorrow'));
    
    $startTimeStamp = strtotime($start_date);
    $endTimeStamp = strtotime($end_date);
    
    $timeDiff = abs($endTimeStamp - $startTimeStamp);
    $numberDays = $timeDiff/86400;
    $numberDays = intval($numberDays);

    $serviceFee = 100;

    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        require_once "config.php";

        $sql = "SELECT * FROM tbl_listings WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = trim($_GET["id"]);
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
        
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $name = $row["name"];
                    $thumbnail = $row["thumbnail"];
                    $description = $row["description"];
                    $price_per_day = $row["price_per_day"];
                } else{
                    header("location: index.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_close($link);
    } else{
        header("location: index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BAMASA</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <style>
            .hero-image {
            /* background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("photographer.jpg"); */
            height: 300px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            }
        </style>
    </head>
  <body class="bg-light">
    <?php include "navbar.php" ?>
    <div class="hero-image" style="background-image: url('upload/listings/<?php echo $thumbnail; ?>');"></div>

    <div class="container bg-white rounded p-3 shadow-sm" style="margin-top: -50px">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="border rounded p-3">
                    <form class="mb-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" min="<?php echo $start_date; ?>" value="<?php echo $start_date; ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" min="<?php echo $end_date; ?>" value="<?php echo $end_date; ?>" required />
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Book Now</button>
                    </form>
                    
                    <p class="mb-0 d-flex align-items-center justify-content-between">
                        <span id="total_days">1 Day</span>
                        <span id="subtotal"><?php echo "₱ ".$price_per_day; ?></span>
                    </p>
                    <p class="d-flex align-items-center justify-content-between">
                        <span>Service Fee</span>
                        <span><?php echo "₱ ".$serviceFee; ?></span>
                    </p>
                    <hr />
                    <h4 class="d-flex align-items-center justify-content-between">
                        <span>Total Price</span>
                        <span id="total_price"><?php echo "₱ ".($price_per_day + $serviceFee); ?></span>
                    </h4>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <h1><?php echo $name; ?></h1>
                    <p><?php echo $description; ?></p>
                </div>

                <h5>Rental Calendar</h5>
                <hr />
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
    <script>
        const price = <?php echo $price_per_day; ?>;
        const serviceFee = <?php echo $serviceFee; ?>;

        const start_date = document.getElementById('start_date');
        const end_date = document.getElementById('end_date');
        let total_price = document.getElementById('total_price');
        let total_days = document.getElementById('total_days');
        let subtotal = document.getElementById('subtotal');

        const inputHandler = function(e) {
            let difference = new Date(end_date.value).getTime() - new Date(start_date.value).getTime();
            let TotalDays = Math.ceil(difference / (1000 * 3600 * 24));
            
            total_days.innerHTML = TotalDays > 1 ? `${TotalDays} Days` : `${TotalDays} Day`;
            subtotal.innerHTML = "₱ " + (price * TotalDays);
            total_price.innerHTML = "₱ " + ((price * TotalDays) + serviceFee);
        }

        start_date.addEventListener('input', inputHandler);
        end_date.addEventListener('input', inputHandler);
    </script>
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          events: [
            {id: 1, title: "", start: "2023-03-03", end: "2023-03-05"}
          ]
        });
        calendar.render();
      });

    </script>

</body>
</html>