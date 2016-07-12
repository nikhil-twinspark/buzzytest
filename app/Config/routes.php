<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'Default', 'action' => 'index', 'index'));
Router::connect('/admin/*', array('controller' => 'admin', 'action' => 'login'));

Router::connect('/logout', array('controller' => 'buzzydoc', 'action' => 'logout'));
Router::connect('/thebuzz', array('controller' => 'buzzydoc', 'action' => 'thebuzz'));
Router::connect('/termcondition', array('controller' => 'buzzydoc', 'action' => 'termcondition'));
Router::connect('/login', array('controller' => 'buzzydoc', 'action' => 'login'));
Router::connect('/dashboard', array('controller' => 'buzzydoc', 'action' => 'dashboard'));
Router::connect('/settings', array('controller' => 'buzzydoc', 'action' => 'settings'));
Router::connect('/searchresult', array('controller' => 'buzzydoc', 'action' => 'searchresult'));
Router::connect('/login/:id/*', array('controller' => 'buzzydoc', 'action' => 'login'), array('id' => '[a-zA-Z0-9=]+'));
Router::connect('/doctor', array('controller' => 'buzzydoc', 'action' => 'doctor'));
Router::connect('/doctor/:id/*', array('controller' => 'buzzydoc', 'action' => 'doctor'), array('id' => '[ a-zA-Z0-9=]+'));
Router::connect('/practice', array('controller' => 'buzzydoc', 'action' => 'practice'));
Router::connect('/practice/:id/*', array('controller' => 'buzzydoc', 'action' => 'practice'), array('id' => '[a-zA-Z]+'));
Router::connect('/ratereview/:id/*', array('controller' => 'buzzydoc', 'action' => 'ratereview'), array('id' => '[ a-zA-Z0-9=]+'));
Router::connect('/clinicratereview/:id/*', array('controller' => 'buzzydoc', 'action' => 'clinicratereview'), array('id' => '[ a-zA-Z0-9=]+'));


Router::connect('/buzzydoc/login/:id/*', array('controller' => 'buzzydoc', 'action' => 'login'), array('id' => '[a-zA-Z0-9=]+'));
Router::connect('/buzzydoc/doctor/:id/*', array('controller' => 'buzzydoc', 'action' => 'doctor'), array('id' => '[a-zA-Z0-9=]+'));
Router::connect('/buzzydoc/practice/:id/*', array('controller' => 'buzzydoc', 'action' => 'practice'), array('id' => '[a-zA-Z]+'));

Router::mapResources('api');
Router::parseExtensions();

CakePlugin::routes();
//Router::connect('/staff/:name/', array('controller'=>'staff','action' => 'login'),array(':name' => '[a-zA-Z]+'));
//Router::connect('/staff/:name/PatientManagement/', array('controller'=>'PatientManagement','action' => 'index'),array(':name' => '[a-zA-Z]+'));
//Router::connect('/staff/:name/PatientManagement/recordpoint/', array('controller'=>'PatientManagement','action' => 'recordpoint'),array(':name' => '[a-zA-Z]+'));
//Router::connect('/staff/:name/PatientManagement/pointallocation/', array('controller'=>'PatientManagement','action' => 'pointallocation'),array(':name' => '[a-zA-Z]+'));
//Router::connect('/staff/:name/PatientManagement/patienthistory/', array('controller'=>'PatientManagement','action' => 'patienthistory'),array(':name' => '[a-zA-Z]+'));
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';