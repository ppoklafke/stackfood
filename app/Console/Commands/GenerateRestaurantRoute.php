<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GenerateRestaurantRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:restaurant-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate restaurant formatted routes';

    /**
     * Execute the console command.
     */

    public function handle(): int
    {
        $routes = Route::getRoutes();
        $restaurantRoutes = collect($routes->getRoutesByMethod()['GET'])->filter(function ($route) {
            return Str::startsWith($route->uri(), 'restaurant-panel');
        });

        $excludeTermsRoute = [
            'print', 'download', 'export', 'edit', 'update', 'invoice', 'child', 'update-default-status', 'update-status',
            'system-currency', 'status', 'paidStatus', 'priority'
        ];

        $excludeTermsAjax = $this->getAjaxRoutes($restaurantRoutes);
        $jsonFilePath = public_path('restaurant_formatted_routes.json');
        $excludeTerms = array_merge($excludeTermsAjax, $excludeTermsRoute);
        $formattedRoutes = [];

        foreach ($restaurantRoutes as $route) {
            $uri = $route->uri();
            $exclude = collect($excludeTerms)->contains(function ($term) use ($uri) {
                return Str::contains($uri, $term);
            });

            if (!$exclude) {
                $hasParameters = preg_match('/\{(.*?)\}/', $uri);
                if (!$hasParameters) {
                    $actualRouteName = $route->getName();
                    $routeName = ucwords(str_replace(['.', '_','-'], ' ', Str::afterLast($actualRouteName, '.')));
                    $bladePath = $this->getBladePathFromController($route);
                    $keywords = $this->getTextDataFromBladeFile($bladePath);
                    $keywords = ucwords(str_replace(['.', '_' ,'-'] ,' ', $keywords));
                    if($bladePath){
                        $formattedRoutes[] = [
                            'routeName' => $routeName,
                            'URI' => $uri,
                            'keywords' => $keywords,
                            'bladePath' =>  $bladePath,
                            'isModified' => false
                        ];
                    }
                }
            }
        }

        if (file_exists($jsonFilePath)) {
            $fileContents = file_get_contents($jsonFilePath);
            $existingRoutes = json_decode($fileContents, true) ?? [];

            $newRoutes = array_filter($formattedRoutes, function ($newRoute) use ($existingRoutes) {
                foreach ($existingRoutes as $existingRoute) {
                    if ($existingRoute['URI'] === $newRoute['URI']) {
                        return false;
                    }
                }
                return true;
            });

            if (!empty($newRoutes)) {
                $updatedRoutes = array_merge($existingRoutes, $newRoutes);
                file_put_contents($jsonFilePath, json_encode($updatedRoutes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } else {
            file_put_contents($jsonFilePath, json_encode($formattedRoutes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return 0;
    }


    function getAjaxRoutes($restaurantRoutes): array
    {
        $jsonRoutes = [];
        $route_names = [];

        foreach ($restaurantRoutes as $route) {
            $uri = $route->uri();
            $action = $route->getAction();

            $controller = $action['controller'] ?? null;
            if ($controller) {
                list($controllerClass, $method) = explode('@', $controller);

                if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                    $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
                    $filename = $reflectionMethod->getFileName();
                    $startLine = $reflectionMethod->getStartLine();
                    $endLine = $reflectionMethod->getEndLine();

                    $file = file($filename);
                    $methodBody = implode('', array_slice($file, $startLine - 1, $endLine - $startLine + 1));

                    if (strpos($methodBody, 'return response()->json') !== false) {
                        $jsonRoutes[] = [
                            'method' => implode('|', $route->methods()),
                            'uri' => $uri,
                            'controller' => $controller
                        ];
                    }
                }
            }
        }

        foreach ($jsonRoutes as $route) {
            $route_names[] = $route['uri'];
        }

        return $route_names;
    }


    function getBladePathFromController($route): ?string
    {
        $action = $route->getAction();
        $controller = $action['controller'] ?? null;

        if ($controller) {
            list($controllerClass, $method) = explode('@', $controller);

            if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
                $filename = $reflectionMethod->getFileName();
                $startLine = $reflectionMethod->getStartLine();
                $endLine = $reflectionMethod->getEndLine();

                $file = file($filename);
                $methodBody = implode('', array_slice($file, $startLine - 1, $endLine - $startLine + 1));

                if (preg_match("/view\\(['\"](.*?)['\"]/", $methodBody, $matches)) {
                    return str_replace('.', '/', $matches[1]);
                }
            }
        }

        return null;
    }

     function getTextDataFromBladeFile($bladePath): ?string
     {
        if(!$bladePath){
            return null;
        }

        $file = 'resources/views/'.$bladePath.'.blade.php';
        $pattern = "/translate\('([^']+)'\)/";
        $textData = [];

        $content = file_get_contents($file);
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $text) {

                $cleanedText = preg_replace("/^messages\./", "", $text);
                $cleanedText = preg_replace("/[_:\?\.,-]+/", " ", $cleanedText);
                $cleanedText = preg_replace("/\d+/", "", $cleanedText);
                $cleanedText = preg_replace("/\s+/", " ", trim($cleanedText));

                $textData[] = $cleanedText;
            }
        }

        $textData = array_unique($textData);
        $finalText = implode(" ", $textData);

         return trim($finalText);
     }
}
