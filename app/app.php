<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/car.php";

    session_start();
    if (empty($_SESSION['list_of_cars'])) {
      $_SESSION['list_of_cars'] = array();
    }
    //put in session start - title may need to change in ''.

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => __DIR__.'/../views'
    ));

    $app['debug'] = true;

    $app->get("/car_matching_search", function() use ($app) {
        return $app['twig']->render ('car_form.html.twig');

    });

     $app->get("/car", function() {

          $first_car = new Car("2014 Porsche 911", 7864, 114991, "img/porsche.jpg");
          $second_car = new Car("2011 Ford F450", 14000, 55995, "img/ford.jpeg");
          $third_car = new Car("2013 Lexus RX 350", 20000, 44700, "img/lexus.jpg");
          $fourth_car = new Car("Mercedes Benz CLS550", 37979, 39900, "img/mercedes.jpg");
          $cars = array($first_car, $second_car, $third_car, $fourth_car);

          $cars_matching_search = array();
              foreach ($cars as $car) {
                  if ($car->worthBuying($_GET["price"], $_GET["miles"])) {
                      array_push($cars_matching_search, $car);
              }
           };

           $output = "";

           if (empty($cars_matching_search)) {
               $output =  "We have no cars for you!";
             } else {
               foreach ($cars_matching_search as $car) {
                   $new_price = $car->getPrice();
                   $miles = $car->getMiles();
                   $make_model = $car->getMake_Model();
                   $picture = $car->getPicture();

                   $output = $output .

                   "<li> $make_model </li>
                   <li> <img src='$picture'>1500 </li>
                  <ul>
                      <li> $new_price </li>
                      <li> $miles </li>
                  </ul>";

              }
            }

        return $output;

        });

        //adding array for the seller below here...
        $app->post("/car_selling", function() use ($app) {
        $cars = new Car($_POST['Make_Model'], $_POST['price']);
        $list_of_cars = array($cars);
        $cars->save();
        return $app['twig']->render('car_selling.html.twig', array('cars' => Car::getAll()));
      };
        //And done...

        return $app;

?>
