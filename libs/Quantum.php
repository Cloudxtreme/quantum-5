<?php
/**
 * Quantum core.
 *
 * @package Quantum
 * @author Gabor Klausz
 */

/**
 * Request-el class-related.
 *
 * @package Quantum
 */
class Request
{
	/** @var string   Page name. */
	public static $pageLanguage = '';

	/** @var string   Actual Uri. */
	public static $requestUri = '';

	/** @var array   Valid language. */
	public static $validLanguage = array('hu', 'en');

	/** @var bool   Multilanguage use. */
	public static $multiLanguage = false;

	/** @var array   Routing rule. */
	public static $routes = array();

	/**
	 * Parses the URL.
	 *
	 * return void
	 */
	public static function requireUri()
	{
		self::setLanguage();
		self::$requestUri = !empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : 'index.html';

		$actionParams = array();
		$controller   = 'index';
		$action       = 'index';
		$layout       = 'index';
		$routingPath  = strpos(self::$requestUri, 'admin') ? 'Admin' : 'Portal';
		self::$routes = (require_once('includes/Routing/' . $routingPath . '/routing.php'));

		foreach (self::$routes as $key => $value) {
			if ($value['uri'][0] == '#' && preg_match($value['uri'], self::$requestUri, $matches) > 0) {
				$controller   = $value['params']['controller'];
				$action       = !empty($value['params']['action']) ? $value['params']['action'] : 'index';
				$actionParams = self::getActionValidParams($matches);
				$layout       = !empty($value['layout']) ? $value['layout'] : $layout;
				break;
			}
			elseif (strpos(self::$requestUri, $value['uri']) !== false) {
				$controller = $value['params']['controller'];
				$action     = !empty($value['params']['action']) ? $value['params']['action'] : 'index';
				$layout     = !empty($value['layout']) ? $value['layout'] : $layout;
				break;
			}
		}

		Site::loader($controller, $action, $actionParams, $layout);
	}

	/**
	 * Returns the associative parametereket and values​​.
	 *
	 * @param array
	 */
	protected static function getActionValidParams($params)
	{
		$return = array();

		if (!empty($params)) {
			foreach ($params as $key => $value) {
				if (!is_numeric($key)) {
					$return[$key] = $value;
				}
			}
		}

		return array_values($return);
	}

	/**
	 * Returns a GET params list to URL.
	 *
	 * @return string
	 */
	public static function getRequestQueryString()
	{
		return !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
	}

	/**
	 * Set language.
	 *
	 * @return void
	 */
	protected function setLanguage()
	{
		$defaultLanguage     = Config::get('default.language');
		$request             = explode('/', $_SERVER['REQUEST_URI']);
		self::$validLanguage = Config::get('enabled.languages');

		if (Config::get('multilanguage')) {
			switch ($request[1]) {
				case '':
				case '/':
				case 'index.php':
					self::$pageLanguage  = $defaultLanguage;
					break;

				default:
					self::$pageLanguage = !empty($request[1]) && in_array($request[1], self::$validLanguage)
						? $request[1]
						: $defaultLanguage;
					break;
			}

			unset($request[1]);
			$_SERVER['REDIRECT_URL'] = implode('/', $request);

			self::$multiLanguage = true;
		}
		else {
			self::$pageLanguage  = $defaultLanguage;
			self::$multiLanguage = false;
		}
	}

	/**
	 * Return the $_GET ertereit rates are defined.
	 *
	 * @param string $key            Array key.
	 * @param string $defaultValue   Default value.
	 */
	public static function getGet($key, $defaultValue = '')
	{
		return isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
	}

	/**
	 * It examines whether there is a $_GET for it.
	 *
	 * @param string $key   Array key.
	 *
	 * @return bool
	 */
	public static function hasGet($key)
	{
		return isset($_GET[$key]);
	}

	/**
	 * Return the $_POST ertereit rates are defined.
	 *
	 * @param string $key            Array key.
	 * @param string $defaultValue   Default value.
	 */
	public static function getPost($key, $defaultValue = '')
	{
		return isset($_POST[$key]) ? $_POST[$key] : $defaultValue;
	}

	/**
	 * It examines whether there is a $_POST for it.
	 *
	 * @param string $key   Array key.
	 *
	 * @return bool
	 */
	public static function hasPost($key)
	{
		return isset($_POST[$key]);
	}

	/**
	 * Return the $_FILES values.
	 *
	 * @param string $key            Array key.
	 * @param array  $defaultValue   Default values.
	 *
	 * @return array
	 */
	public static function getFiles($key, $defaultValue = array())
	{
		return !empty($_FILES[$key]['name']) ? $_FILES : $defaultValue;
	}

	/**
	 * It examines whether there is a $_FILES for it.
	 *
	 * @param string $key   Array key.
	 *
	 * @return bool
	 */
	public static function hasFiles($key)
	{
		return !empty($_FILES[$key]['name']);
	}

	/**
	 * Redirected to the page url given on the basis.
	 *
	 * @param string $url   Url.
	 *
	 * @return void
	 */
	public static function redirect($url)
	{
		header('Location: ' . $url);
		exit;
	}

	/**
	 * Examine the request that is received by an AJAX
	 *
	 * @return bool
	 */
	public function isAjaxMethod()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		else {
			return false;
		}
	}
}

/**
 * Class cache related.
 *
 * @package Quantum
 */
class Cache
{
	/** @var object   Memcache connection. */
	public $memcache;

	/**
	 * Init a Memcache connection.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if (empty($this->memcache)) {
			$this->memcache = new Memcache;
			$this->memcache->connect(Config::get('memcache.host'), Config::get('memcache.port'))
				or die ("Could not connect");
		}
	}

	/**
	 * Returns the cache data coming from
	 *
	 * @param string $key   Cache key.
	 *
	 * @return void
	 */
	public function get($key)
	{
		return $this->memcache->get($key);
	}

	/**
	 * Sets the cached data.
	 *
	 * @param string $key      Cache key.
	 * @param mix    $data     Data.
	 * @param int    $expire   Expiration date.
	 *
	 * @return void
	 */
	public function set($key, $data, $expire)
	{
		return $this->memcache->set($key, $data, false, $expire);
	}

	/**
	 * Deletes the cache data coming from.
	 *
	 * @param string $key   Cache key.
	 *
	 * @return void
	 */
	public function delete($key)
	{
		return $this->memcache->delete($key);
	}
}

/**
 * DbFactory-el class related.
 *
 * @package Quantum
 */
class DbFactory
{
	/**
	 * DB conecton.
	 *
	 * @param string $database   DB.
	 *
	 * @return Object
	 */
	public static function connect($database = '')
	{
		return new Mysql($database != '' ? $database : Config::get('database.connect.database'));
	}
}

/**
 * Mysql class related.
 *
 * @package Quantum
 */
class Mysql
{
	/** @var string   DB. */
	protected $database;

	/** @var resource   DB connection. */
	protected $connection;

	/**
	 * Construct.
	 *
	 * @param string $database   DB name.
	 *
	 * @return void
	 */
	public function __construct($database)
	{
		$this->database = $database;

		if (empty($this->connection)) {
			$this->connection = mysql_connect(
				Config::get('database.connect.host'),
				Config::get('database.connect.user'),
				Config::get('database.connect.password')
			);

			if (!$this->connection) {
				die('DB connect error: ' . mysql_error() . ' - ' . mysql_errno());
			}
		}

		if (!mysql_select_db($this->database, $this->connection)) {
			die ('Can\'t use foo : ' . mysql_error() . ' - ' . mysql_errno());
		}

		mysql_query('SET NAMES ' . Config::get('database.connect.charset'));
	}

	/**
	 * Query processor.
	 *
	 * @param string $query   SQL command.
	 *
	 * @return Array
	 */
	public function query($query)
	{
					$executeTime = microtime(true);
		$result      = mysql_query($query);
		$executeTime = microtime(true) - $executeTime;
		$mysqlError  = mysql_error($this->connection);

		// MYSQL log.
		Logger::logSet($query, $executeTime, $mysqlError);

		if (!empty($mysqlError)) {
			echo mysql_errno($this->connection) . ': ' . $mysqlError . "\n" . '<pre>' . $query . '</pre>' . "\n";
			exit;
		}

		return new DbResult($result);
	}

	/**
	 * Scroll methods, the resulting query is processed.
	 *
	 * @param string $query         SQL query.
	 * @param int    $curremtPage   Offset.
	 * @param int    $rowsPerPage   Limit.
	 * @param int    $count         Line count.
	 *
	 * @return array
	 */
	public function pagerQuery($query, $curremtPage = 1, $rowsPerPage = 10, &$count)
	{
		$allElement  = array();
		$offset      = ($curremtPage - 1) * $rowsPerPage;
		$executeTime = microtime(true);
		$query       = trim(str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $query) . ' LIMIT '. $offset . ', ' .
			$rowsPerPage);
		$result      = mysql_query($query);
		$executeTime = microtime(true) - $executeTime;
		$mysqlError  = mysql_error($this->connection);

		// MYSQL log.
		Logger::logSet($query, $executeTime, $mysqlError);

		if (!empty($mysqlError)) {
			echo mysql_errno($this->connection) . ': ' . $mysqlError . "\n" . '<pre>' . $query . '</pre>' . "\n";
			exit;
		}

		$result2 = mysql_query('SELECT FOUND_ROWS() AS countRows');
		$row     = mysql_fetch_assoc($result2);
		$count   = $row['countRows'];

		return new DbResult($result);
	}

	/**
	 * Executes a query pager. The queries can only SELECT, and must not contain LIMIT and OFFSET
	 * Setup. (If the connection is configured logger object, it is logged in as well.)
	 *
	 * @param string  $query        Query
	 * @param array   $params       The query parameters value. The value is determined by the type
	 * @param int     $pageNumber   The requested number (1 to indexed).
	 * @param int     $item         Spermine page to display the number of elements on a page.
	 * @param int    &$itemCount    Your (result) numItems Without Scroll Next. If the Input value is set,
	 *                              And the value is FALSE, the output value will not be filling out. (Output parameter).
	 *
	 * @throws PDOException if an error has occurred in the query to the User. DbResult
	 * @return the query results.
	 */
	public function queryPaged2($query, $curremtPage = 1, $rowsPerPage = 10, &$count = false)
	{
		if ($count !== false) {
			$executeTime = microtime(true);
			$countQuery  = '
				SELECT
					COUNT(*) AS count
				FROM
					('.$query.') T
			';
			$result      = mysql_query($countQuery);
			$countField  = mysql_fetch_assoc($result);
			$count       = $countField['count'];
			usleep(1);
			$executeTime = microtime(true) - $executeTime;
			$mysqlError  = mysql_error($this->connection);
			// MYSQL log.
			Logger::logSet($countQuery, $executeTime, $mysqlError);
		}

		$executeTime = microtime(true);
		$query .= '
			LIMIT
				'.(int)$rowsPerPage.'
			OFFSET
				'.(int)(($curremtPage - 1) * $rowsPerPage)
		;
		$result      = mysql_query($query);
		usleep(1);
		$executeTime = microtime(true) - $executeTime;
		$mysqlError  = mysql_error($this->connection);

		// MYSQL log.
		Logger::logSet($query, $executeTime, $mysqlError);

		return new DbResult($result);
	}

	/**
	 * Destruct.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		mysql_close($this->connection);
	}
}

/**
 * Class on SQL results..
 *
 * @package Quantum
 */
class DbResult
{
	/** @var resource   Db result. */
	protected $result;

	/**
	 * Construct.
	 *
	 * @return $result   Db result.
	 *
	 * @return void
	 */
	public function __construct($result)
	{
		$this->result = $result;
	}

	/**
	 * Returns a field from the query.
	 *
	 * @return array
	 */
	public function fetchOne()
	{
		$return = mysql_fetch_array($this->result);
		return $return[0];
	}

	/**
	 * Returns a row in the query.
	 *
	 * @return array
	 */
	public function fetchRow()
	{
		return mysql_fetch_assoc($this->result);
	}

	/**
	 * Returns all rows of the query.
	 *
	 * @return array
	 */
	public function fetchAll()
	{
		$return = array();

		while ($row = $this->fetchRow()) {
			$return[] = $row;
		}

		return $return;
	}
}

/**
 * Template class-related.
 *
 * @package Quantum
 */
class Template
{
	/** @var mix   Menu element. */
	public $layoutLeftMenu;

	/** @var mix   Menu name. */
	public $layoutLeftMenuName;

	/** @var bool   Is logged in status. */
	public $isLogin = false;

	/** @var mix   Template path. */
	public $path = '';

	/** @var mix   Header JS stack. */
	public $headerJavascripts = array();

	/** @var mix   Footer JS stack. */
	public $footerJavascripts = array();

	/** @var array   Szukseges css-eket tarolo tomb. */
	public $headerCss = array();

	/** @var mix   Template header title. */
	public $headerTitle = '';

	/** @var mix   Template description. */
	public $headerDescription = '';

	/** @var mix   Template keywords. */
	public $headerKeywords = '';

	/** @var string   Template directory. */
	public $templateDir = 'View/templates/';

	/** @var string   Generated content HTML. */
	public $content = '';

	/** @var string   Layout */
	public $layout  = '';

	/** @var string   Template name. */
	public $templateFileName  = '';

	/** @var string   Header type */
	public $headerOutputType = 'html';

	/**
	 * PreDispatch.
	 *
	 * @return void
	 */
	public function preDispatch($actionName)
	{
	}

	/**
	 * PostDispatch.
	 *
	 * @param string $templateFileName   Template file name.
	 * @param string $page               Path.
	 *
	 * @return void
	 */
	public function postDispatch($templateFileName, $page)
	{
		View::set('scriptTime', (microtime(true) - SCRIPT_START));
		View::set('mysqlLog', Logger::logGetAll());

		switch ($this->headerOutputType) {
			case 'html':
				$this->setContent($templateFileName, $page);
				$this->setLayout($page);
				$this->render();
				break;
			case 'json':
				$this->jsonRender();
				break;
			case 'text':
				$this->textRender();
				break;
		}
	}

	/**
	 * Loads the template file.
	 *
	 * @param string $templateFileName   Template file name.
	 * @param string $page               Path.
	 *
	 * @return void
	 */
	protected function setContent($templateFileName, $page)
	{
		ob_start();

		include($this->templateDir . $this->path .  $templateFileName . '.php');

		$this->content = ob_get_clean();
	}

	/**
	 * Loads the layout file.
	 *
	 * @param string $page   Path.
	 *
	 * @return void
	 */
	protected function setLayout($page)
	{
		ob_start();

		include('View/layout/' . $page . '_layout.php');

		$this->layout = ob_get_clean();
	}

	/**
	 * Loads the block file.
	 *
	 * @param string $blockName   Block file name.
	 *
	 * @return void
	 */
	public function includeBlock($blockName)
	{
		ob_start();

		include('View/block/' . $this->path . $blockName . '.php');

		$block = ob_get_clean();

		echo $block;
	}

	/**
	 * Sets needed header js files.
	 *
	 * @param $file      File name.
	 * @param $package   If a framework, it is organized into folders and its access routes.
	 *
	 * @return void
	 */
	public function setHeaderJavascript($file, $package = '')
	{
		$this->headerJavascripts[] = '/static/js/' . (!empty($package) ? $package . '/' : $this->path) . $file . '.js';
	}

	/**
	 * Sets needed footer js files.
	 *
	 * @param $file      File name.
	 * @param $package   If a framework, it is organized into folders and its access routes.
	 *
	 * @return void
	 */
	public function setFooterJavascript($file, $package = '')
	{
		$this->footerJavascripts[] = '/static/js/' . (!empty($package) ? $package . '/' : $this->path) . $file . '.js';
	}

	/**
	 * Returns the necessary header js files.
	 *
	 * @return void
	 */
	public function getHeaderJavascripts()
	{
		return $this->headerJavascripts;
	}

	/**
	 * Returns the necessary footer js files.
	 *
	 * @return void
	 */
	public function getFooterJavascripts()
	{
		return $this->footerJavascripts;
	}

	/**
	 * Sets needed header css files.
	 *
	 * @param $file      File name.
	 * @param $package   If a framework, it is organized into folders and its access routes.
	 *
	 * @return void
	 */
	public function setHeaderCss($file, $package = '')
	{
		$this->headerCss[] = '/static/css/' . (!empty($package) ? $package . '/' : $this->path) . $file . '.css';
	}

	/**
	 * Returns the required css files.
	 *
	 * @return void
	 */
	public function getHeaderCss()
	{
		return $this->headerCss;
	}

	/**
	 * HTML viewing.
	 *
	 * @return void
	 */
	public function render()
	{
		echo $this->layout;
	}

	/**
	 * JSON viewing.
	 *
	 * @return void
	 */
	public function jsonRender()
	{
		echo json_encode(View::$datas['result']);
	}

	/**
	 * Text viewing.
	 *
	 * @return void
	 */
	public function textRender()
	{
		echo View::$datas['result'];
	}

	/**
	 * Translation
	 *
	 * @param string $key   key.
	 *
	 * @return string
	 */
	public function _($key)
	{
		return !empty(Site::$translateValues[$key]) ? Site::$translateValues[$key] : $key;
	}
}

/**
 * The template asses handed and returned to storage object val.
 *
 * @package Quantum
 */
class View
{
	public static $datas;

	/**
	 * Stored in the key by value.
	 *
	 * @param string $key     Array key.
	 * @param mixed  $value   Variable.
	 *
	 * @return void
	 */
	public static function set($key, $value)
	{
		self::$datas[$key] = $value;
	}

	/**
	 * Returns the stored value.
	 *
	 * @param string $key       Array key.
	 * @param string $default   Default value.
	 *
	 * @return mixed
	 */
	public static function get($key, $default = null)
	{
		return !empty(self::$datas[$key]) ? self::$datas[$key] : $default;
	}
}

/**
 * Page class is responsible for starting up.
 *
 * @package Quantum
 */
class Site
{
	/** @var array   Static language values. */
	public static $translateValues = array();

	/** @var array   Actual controller. */
	public static $controller = array();

	/**
	 * Initiate a controller and methods.
	 *
	 * @param string $controller     Controller name.
	 * @param string $action	     Methodus name.
	 * @param array  $actionParams   Action params.
	 *
	 * @return void
	 */
	public static function loader($controller, $action, $actionParams = array(), $pageLayout)
	{
		self::$controller = $controller;

		if (strpos($controller, 'admin') == 0) {
			self::$controller = strtolower(substr(self::$controller, 5));
		}

		// If you are multilingual and then stores the stat variables.
		if (Request::$multiLanguage) {
			$filename = '../cache/lang/' . Request::$pageLanguage . '.php';
			self::$translateValues = file_exists($filename)
				? include ($filename)
				: include ('../cache/lang/en.php');
		}

		$templateFileName = $controller . '_' . $action;
		$controller       = ucfirst($controller)  . 'Controller';
		$actionName       = $action;
		$action           = 'do' . ucfirst($action);
		$page             = new $controller;
		$page->path       = (strpos($pageLayout, 'admin') === false) ? $page->path : 'admin/';

		$page->preDispatch($actionName);

		// Loads the appropriate controller is.
		call_user_func_array(array($page, $action), $actionParams);

		$pageLayout = $pageLayout != 'admin' ? $pageLayout : 'admin/' . $pageLayout;

		$page->postDispatch($templateFileName, $pageLayout);
	}

	/**
	 * Static content translation.
	 *
	 * @param $value
	 */
	public static function translate($key)
	{
		return self::$translateValues[$key];
	}
}

/**
 * Controllerek parent class.
 *
 * @package Quantum
 */
class PageController extends Template
{
	protected $count       = 0;
	protected $currentPage = 1;
	protected $rowsPerPage = 10;

	public function __call($method, $params)
	{
		// 404
		print '<pre>';
		var_dump('Nothing Template Page!:)', $method, $params);
		print '</pre>';
		exit;
	}
}

/**
 * Config for adjusting class.
 *
 * @package Quantum
 */
class Config
{
	/** @var array   Config beallitasokat tartalmazo tomb. */
	public static $configDatas = array();

	/**
	 * A variable is set.
	 *
	 * @param string $key    Array key.
	 * @param string $data   Value.
	 *
	 * @return void
	 */
	public static function set($key, $data = null)
	{
		self::$configDatas[$key] = $data;
	}

	/**
	 * A array is set.
	 *
	 * @param string $data   Data.
	 *
	 * @return void
	 */
	public static function setArray($datas = null)
	{
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				self::$configDatas[$key] = $data;
			}
		}
	}

	/**
	 * Returns a variable is set.
	 *
	 * @param string $key       Array key.
	 * @param string $default   Default value.
	 *
	 * @return void
	 */
	public static function get($key, $default = null)
	{
		return isset(self::$configDatas[$key]) ? self::$configDatas[$key] : $default;
	}
}

/**
 * Mysql log.
 *
 * @package Quantum
 */
class Logger
{
	/** @var  array   MySql log array. */
	public static $mysqlLog = array();

	/**
	 * Enrolled in the SQL runtime.
	 *
	 * @param $query         Sql query.
	 * @param $executeTime   Running time.
	 * @param $error         Error.
	 *
	 * @return void
	 */
	public static function logSet($query, $executeTime, $error = '')
	{
		self::$mysqlLog[] = array(
			'query'       => $query,
			'executeTime' => substr($executeTime, 0, 6) . ' second',
			'error'       => $error,
		);
	}

	/**
	 * Returns the SQL data.
	 *
	 * @return array
	 */
	public static function logGetAll()
	{
		return self::$mysqlLog;
	}
}