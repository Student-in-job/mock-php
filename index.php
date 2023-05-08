<?php
    require_once("Request.php");
    require_once("Route.php");

    $router = new Route();
    $request = new Request($_REQUEST);
    $server = strtolower($_SERVER["SERVER_SOFTWARE"]);
    if(strpos($server, 'nginx') !== false)
    {
        if(isJSON())
        {
            $data = file_get_contents('php://input');
            $request->addJSON($data);
        }
    }
    elseif (strpos($server, 'apache') !== false)
    {
        if($request->countElements() == 0)
        {
            if($request->isPOST() || $request->isPUT())
            {
                $data = file_get_contents('php://input');
                $request->addJSON($data);
            }
        }
    }



    if($router->hasRoute($_SERVER["REQUEST_URI"]))
    {
        callFunction($router->getRoute($_SERVER["REQUEST_URI"]), $request);
    }
    else if($router->hasParamRoute($_SERVER["REQUEST_URI"]))
    {
        $params = explode('/', $_SERVER["REQUEST_URI"]);
        $token = $params[sizeof($params) - 1];
        $request->token = $token;
        callFunction($router->getParamRoute($_SERVER["REQUEST_URI"]), $request);
    }
    else
    {
        header("HTTP/1.1 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
    function isJSON()
    {
        return $_SERVER["CONTENT_TYPE"] == "application/json";
    }

    function callFunction($route, $request)
    {
        $params = explode("/", $route);
        $controllerPath = "controller/" . $params[0] . ".php";
        require_once($controllerPath);
        $className = $params[0];
        $methodName = $params[1];
        $object = new $className;
        echo $object->$methodName($request);
    }

    function printVariables($variable){
        if(!$variable)
        {
            echo "empty<br/>";
            return;
        }
        foreach($variable as $mkey => $mvalue){
            echo $mkey . " = " . $mvalue . "<br/>";
        }
    }
    function printRequest(){
        var_dump($_REQUEST);
    }
?>