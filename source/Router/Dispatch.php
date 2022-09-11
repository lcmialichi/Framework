<?php

namespace Source\Router;

/**
 * Class CoffeeCode Dispatch
 *
 * @author Robson V. Leite <https://github.com/robsonvleite>
 * @package CoffeeCode\Router
 */
abstract class Dispatch
{
    use RouterTrait;

    /** @var string */
    protected string $projectUrl;

    /** @var string */
    protected string $httpMethod = "";

    /** @var string */
    protected string $path;

    /** @var array|null */
    public ?array $route = null;

    /** @var array */
    protected array $routes;

    /** @var string */
    protected string $separator;

    /** @var string|null */
    protected ?string $namespace = null;

    /** @var string|null */
    protected ?string $group = null;

    /** @var array|null */
    protected ?array $middleware = null;

    /** @var array|null */
    protected ?array $data = null;

    /** @var int */
    protected ?int $error = null;

    /** @var array */
    protected ?array $query = [];

    /**
     * Dispatch constructor.
     *
     * @param string $projectUrl
     * @param null|string $separator
     */
    public function __construct(string $projectUrl, ?string $separator = ":")
    {
        $this->projectUrl = (substr($projectUrl, "-1") == "/" ? substr($projectUrl, 0, -1) : $projectUrl);
        $this->path = rtrim((filter_input(INPUT_GET, "route", FILTER_DEFAULT) ?? "/"), "/");
        $this->query = filter_input_array(INPUT_GET);
        unset($this->query["route"]);
         
        $this->separator = ($separator ?? ":");
        $this->setHttpMethod();

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');

        if($this->httpMethod == "OPTIONS"){     
            exit;
        }
        
    }

    public function setHttpMethod(?string $httpMethod = null){
        $this->httpMethod = $httpMethod ?? $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->routes;
    }

    /**
     * @param string $name
     * @param array|null $data
     * @return string|null
     */
    public function route(string $name, array $data = null): ?string
    {
        foreach ($this->routes as $http_verb) {
            foreach ($http_verb as $route_item) {
                if (!empty($route_item["name"]) && $route_item["name"] == $name) {
                    return $this->treat($route_item, $data);
                }
            }
        }
        return null;
    }

    /**
     * @param null|string $namespace
     * @return Dispatch
     */
    public function namespace(?string $namespace): Dispatch
    {
        $this->namespace = ($namespace ? ucwords($namespace) : null);
        return $this;
    }

    /**
     * @param null|string $group
     * @return Dispatch
     */
    public function group(?string $group, array|string $middleware = null): Dispatch
    {

        $this->group = ($group ? trim($group, "/") : null);
        $this->middleware = $middleware ? [$this->group => $middleware] : null;
        return $this;
    }

    /**
     * @return null|array
     */
    public function data(): ?array
    {
        return $this->data;
    }

    /**
     * @return object|null
     */
    public function current(): ?object
    {
        return (object)array_merge(
            [
                "namespace" => $this->namespace,
                "group" => $this->group,
                "path" => $this->path
            ],
            $this->route ?? []
        );
    }

    /**
     * @return string
     */
    public function home(): string
    {
        return $this->projectUrl;
    }

    /**
     * @param string $route
     * @param array|null $data
     */
    public function redirect(string $route, array $data = null): void
    {
        if ($name = $this->route($route, $data)) {
            header("Location: {$name}");
            exit;
        }

        if (filter_var($route, FILTER_VALIDATE_URL)) {
            header("Location: {$route}");
            exit;
        }

        $route = (substr($route, 0, 1) == "/" ? $route : "/{$route}");
        header("Location: {$this->projectUrl}{$route}");
        exit;
    }

    /**
     * @return null|int
     */
    public function error(): ?int
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function dispatch(): bool
    {
        if (empty($this->routes) || empty($this->routes[$this->httpMethod])) {
            $this->error = NOT_IMPLEMENTED;
            return false;
        }

        $this->route = null;
        foreach ($this->routes[$this->httpMethod] as $key => $route) {
            if (preg_match("~^" . $key . "$~", $this->path, $found)) {
                $this->route = $route;
            }
        }

        return $this->execute();
    }
}