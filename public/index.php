<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/PersonalController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ComandaController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/Logger.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/app');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Bienvenido a la comanda");
    return $response;
});

// peticiones
$app->group('/personal', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PersonalController::class . ':TraerTodos');
    $group->get('/{legajo}', \PersonalController::class . ':TraerUno');
    $group->get('/perfil/{perfil}', \PersonalController::class . ':TraerPorPerfil');
    $group->post('[/]', \PersonalController::class . ':CargarUno');
  });

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{tipo}', \ProductoController::class . ':TraerPorTipo');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{codigo}', \MesaController::class . ':TraerUno');
  $group->get('/estado/{estado}', \MesaController::class . ':TraerPorEstado');
  $group->post('[/]', \MesaController::class . ':CargarUno');
});

$app->group('/comanda', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ComandaController::class . ':TraerTodos');
  $group->post('[/]', \ComandaController::class . ':CargarUno');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');  
  $group->get('/{prd_tipo}', \PedidoController::class . ':TraerPorTipo');  
});

// Run app
$app->run();

