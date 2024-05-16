<?php
require '../vendor/autoload.php'; 
require "./services/BaseService.php";
require "./services/UserService.php";
require "./services/TipsService.php";
require "./services/TasksService.php";
require "./services/NotesService.php";
require "./services/ExamsService.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


Flight::register('base_services', "BaseService");
Flight::register('user_service', "UserService");
Flight::register('tips_service', "TipsService");
Flight::register('tasks_service', "TasksService");
Flight::register('notes_service', "NotesService");
Flight::register('exams_service', "ExamsService");


require './routes/UserRoutes.php';
require './routes/TipsRoutes.php';
require './routes/TasksRoutes.php';
require './routes/NotesRoutes.php';
require './routes/ExamsRoutes.php';

Flight::start();
?>