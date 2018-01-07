<?php
class Router {
    private $routes = [
        "^([a-zA-Z0-9-_]+)\/?$",
        "^([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/?$",
        "^([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/?$",
        "^([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/?$"
    ];
    private $parameters = [];
    public function __construct($url) {
        foreach ($this->routes as $route) {
            if (!preg_match("/" . $route . "/", $url, $matches))
                continue;
            $this->parameters = array_slice($matches, 1);
        }
    }
    public function getParameter($index){
      if(isset($this->parameters[($index-1)])){
        return $this->parameters[($index-1)];
      }else{
        return "";
      }
    }
}
