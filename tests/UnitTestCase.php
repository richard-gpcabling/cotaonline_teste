<?php

use Phalcon\Di;
use Phalcon\DI\FactoryDefault;
use \Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{

    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(Phalcon\DiInterface $di = null, Phalcon\Config $config = null)
    {
        global $config;

        // Load any additional services that might be required during testing
        $di = new FactoryDefault();

        $di->set('config', function() use ($config) {
            return $config;
        });

        $di->set('mailer', function () use ($config) {
            $config = $config->get('mail')->toArray();
            //Create a new PHPMailer instance
            $mailer = new \PHPMailer\PHPMailer\PHPMailer();
            //Tell PHPMailer to use SMTP
            $mailer->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mailer->SMTPDebug = $config['SMTPDebug'];
            //Ask for HTML-friendly debug output
            $mailer->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mailer->Host = $config['Host'];
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mailer->Port = (int) $config['Port'];
            //Whether to use SMTP authentication
            $mailer->SMTPAuth = true;
            // CharSet
            $mailer->CharSet = $config['CharSet'];
            // SSL or TLS
            $mailer->SMTPSecure = $config['SMTPSecure'];
            //Username to use for SMTP authentication
            $mailer->Username = $config['Username'];
            //Password to use for SMTP authentication
            $mailer->Password = $config['Password'];
            //Set who the message is to be sent from
            $mailer->setFrom($config['Username'], 'Grupo Policom');

            return $mailer;
        });

        $di->set('db', function () use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });

        // get any DI components here. If you have a config, be sure to pass it to the parent
        $this->setConfig($config);
        $this->setDI($di);

        Di::setDefault($di);

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
           //throw new Exception('Please run parent::setUp().');
        }
    }
}
