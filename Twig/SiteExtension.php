<?php

namespace CoreSys\SiteBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Common site extensions to be used in conjunction with
 * the CoreSys Bundles
 *
 * Class SiteExtension
 * @package CoreSys\SiteBundle\Twig
 */
class SiteExtension extends BaseExtension
{

    /**
     * @var string
     */
    protected $name = 'site_extension';

    private $generator;

    public function __construct( EntityManager $entity_manager, ContainerInterface $container, Session $session, UrlGeneratorInterface $generator )
    {
        parent::__construct( $entity_manager, $container, $session ); // TODO: Change the autogenerated stub
        $this->generator = $generator;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'count'   => new \Twig_Filter_Method( $this, 'getFilterArrayCount' ),
            'dump'    => new \Twig_Filter_Method( $this, 'getFilterVarDump' ),
            'strip'   => new \Twig_Filter_Method( $this, 'getFilterStrip' ),
            'ucwords' => new \Twig_Filter_Method( $this, 'getFilterUcWords' ),
        );
    }

    /**
     * @param array $array
     *
     * @return int
     */
    public function getFilterArrayCount( $array = array() )
    {
        $array = is_array( $array ) ? $array : array();

        return count( $array );
    }

    /**
     * @param null $obj
     *
     * @return string
     */
    public function getFilterVarDump( $obj = NULL )
    {
        return $this->getVarDumpFunc( $obj );
    }

    /**
     * get the vardump of an item
     *
     * @param mixed $item
     *
     * @return string
     */
    public function getVarDumpFunc( $item = NULL )
    {
        ob_start();
        var_dump( $item );
        $contents = ob_get_clean();

        return $contents;
    }

    /**
     * @param null $string
     *
     * @return mixed
     */
    public function getFilterStrip( $string = NULL )
    {
        $string = preg_replace( '/[^a-zA-Z0-9.\s]+/', '', $string );

        return $string;
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getFilterUcWords( $string = NULL )
    {
        return ucwords( $string );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'base_path'        => new \Twig_Function_Method( $this, 'getBasePath' ),
            'varDump'          => new \Twig_Function_Method( $this, 'getVarDumpFunc' ),
            'getHost'          => new \Twig_Function_Method( $this, 'getHost' ),
            'date'             => new \Twig_Function_Method( $this, 'getDate' ),
            'dateFuture'       => new \Twig_Function_Method( $this, 'getFutureDate' ),
            'strtolower'       => new \Twig_Function_Method( $this, 'getStrtolower' ),
            'intval'           => new \Twig_Function_Method( $this, 'getIntval' ),
            'substr'           => new \Twig_Function_Method( $this, 'getSubstr' ),
            'contains'         => new \Twig_Function_Method( $this, 'getContains' ),
            'randomFromArray'  => new \Twig_Function_Method( $this, 'getRandomFromArray' ),
            'browser'          => new \Twig_Function_Method( $this, 'getBrowser' ),
            'isDev'            => new \Twig_Function_Method( $this, 'isDev' ),
            'siteSetting'      => new \Twig_Function_Method( $this, 'getSiteSetting' ),
            'str_replace'      => new \Twig_Function_Method( $this, 'getStrReplace' ),
            'rtrim'            => new \Twig_Function_Method( $this, 'getRtrim' ),
            'routeContains'    => new \Twig_Function_Method( $this, 'getRouteContains' ),
            'printArray'       => new \Twig_Function_Method( $this, 'printArray' ),
            'numberFormat'     => new \Twig_Function_Method( $this, 'getNumberFormat' ),
            'timestamp'        => new \Twig_Function_Method( $this, 'getTimestamp' ),
            'md5'              => new \Twig_Function_Method( $this, 'getMD5' ),
            'empty'            => new \Twig_Function_Method( $this, 'isEmpty' ),
            'isset'            => new \Twig_Function_Method( $this, 'isIsset' ),
            'implode'          => new \Twig_Function_Method( $this, 'getImplode' ),
            'siteLogo'         => new \Twig_Function_Method( $this, 'getLogo', array( 'is_safe' => array( 'html' ) ) ),
            'siteAltLogo'      => new \Twig_Function_Method( $this, 'getAltLogo', array( 'is_safe' => array( 'html' ) ) ),
            'ob_start'         => new \Twig_Function_Method( $this, 'getObStart', array( 'is_safe' => array( 'html' ) ) ),
            'ob_get_clean'     => new \Twig_Function_Method( $this, 'getObGetClean', array( 'is_safe' => array( 'html' ) ) ),
            'renderTwigString' => new \Twig_Function_Method( $this, 'renderTwigString', array( 'is_safe' => array( 'html' ) ) ),
            'unserialize'      => new \Twig_Function_Method( $this, 'getUnserialize', array( 'is_safe' => array( 'html' ) ) ),
            'defineVariable'   => new \Twig_Function_Method( $this, 'defineVariable', array( 'is_safe' => array( 'html' ) ) ),
            'redirect'         => new \Twig_Function_Method( $this, 'performRedirect', array( 'is_safe' => array( 'html' ) ) ),
            'url'              => new \Twig_Function_Method( $this, 'getUrl' ),
        );
    }

    /**
     * Override the default url function and add an urlencode option
     */
    public function getUrl($name, $parameters = array(), $schemeRelative = false, $encode = false )
    {
        $url = $this->generator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
        if( $encode ) {
            return urlencode( $url );
        }
        return $url;
    }

    public function performRedirect( $location = null, $html = false, $time = 0 )
    {
        if( $html ) {
            $time = intval( $time );
            echo '<meta http-equiv="refresh" content="' . $time . '; url=' . $location . '" />';
        } else {
            header( 'Location: ' . $location );
        }
    }

    public function defineVariable( $var = NULL, $val = NULL )
    {
        if ( !empty( $var ) ) {
            defined( $var ) || define( $var, $val );
            echo 'DEFINED ' . $var;
        }

        return NULL;
    }

    public function getUnserialize( $obj = NULL )
    {
        try {
            $obj = unserialize( $obj );

            return $obj;
        } catch ( \Exception $e ) {
            return NULL;
        }
    }

    public function getObStart()
    {
        ob_start();
    }

    public function getObGetClean()
    {
        $content = ob_get_clean();

        return $content;
    }

    public function renderTwigString( $string = NULL )
    {
        try {
            $twig   = $this->get( 'twig' );
            $string = $twig->render( $string );
        } catch ( \Exception $e ) {
            throw new \Twig_Error( $e->getMessage(), $e->getLine(), $e->getFile(), $e->getPrevious() );
        }

        return $string;
    }

    public function getImplode( $delimeter = NULL, array $array = array() )
    {
        $array = is_array( $array ) ? $array : array();

        return implode( $delimeter, $array );
    }

    public function isIsset( $var = NULL )
    {

    }

    public function isEmpty( $val = NULL )
    {
        return empty( $val );
    }

    public function getLogo( $class = NULL, $attr = array() )
    {
        $twig     = $this->getContainer()->get( 'core_sys_media.media_extension' );
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $logo     = $settings->getLogo();
        $extra    = array( 'class' => $class, 'attr' => $attr );

        if ( !empty( $logo ) ) {
            return $twig->getFullImage( $logo->getId(), 'original', TRUE, $extra );
        }

        return NULL;
    }

    public function getAltLogo( $class = NULL, $attr = array() )
    {
        $twig     = $this->getContainer()->get( 'core_sys_media.media_extension' );
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $logo     = $settings->getAltLogo();
        $extra    = array( 'class' => $class, 'attr' => $attr );

        if ( !empty( $logo ) ) {
            return $twig->getFullImage( $logo->getId(), 'original', TRUE, $extra );
        }

        return NULL;
    }

    public function getTimestamp()
    {
        return time();
    }

    public function getMD5( $string = NULL )
    {
        return md5( $string );
    }

    public function getNumberFormat( $number, $decimals = 0, $dec_point = '.', $thousands_sep = ',' )
    {
        $number   = intval( $number );
        $decimals = intval( $decimals );

        return number_format( $number, $decimals, $dec_point, $thousands_sep );
    }

    public function getBasePath( $path )
    {
        $path = str_replace( 'script_base_url', '', $path );
        $path = str_replace( 'app_dev.php', '', $path );
        $path = str_replace( 'app.php', '', $path );
        $path = str_replace( '//', '/', $path );
        $path = str_replace( '//', '/', $path );

        return $path;
    }

    function printArray( $array, $delim = "\n" )
    {
        if ( !is_array( $array ) ) {
            return $array;
        }

        return implode( $delim, $array );
    }

    /**
     * @param null $num
     *
     * @return int
     */
    public function getIntval( $num = NULL )
    {
        return intval( $num );
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getStrtolower( $string = NULL )
    {
        return strtolower( $string );
    }

    /**
     * @param null $string
     *
     * @return bool|string
     */
    public function getRouteContains( $string = NULL )
    {
        if ( empty( $string ) ) {
            return FALSE;
        }

        $request = $this->getContainer()->get( 'request' );
        $route   = $request->get( '_route' );
        $route   = strtolower( trim( $route ) );

        $string = strtolower( trim( $string ) );

        return strstr( $route, $string );
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $_SERVER[ 'HTTP_HOST' ];
    }

    /**
     * @param $string
     * @param $trim
     *
     * @return string
     */
    public function getRtrim( $string, $trim )
    {
        return rtrim( $string, $trim );
    }

    /**
     * @param $match
     * @param $replace
     * @param $string
     *
     * @return mixed
     */
    public function getStrReplace( $match, $replace, $string )
    {
        $string = str_replace( $match, $replace, $string );

        return $string;
    }

    /**
     *
     */
    public function getSiteSetting( $var = NULL, $default = NULL )
    {
        $config = $this->getRepo( 'CoreSysSiteBundle:Config' );
        $config = $config->getConfig();

        $function = 'get' . str_replace( ' ', '', ucwords( str_replace( '_', ' ', $var ) ) );
        if ( method_exists( $config, $function ) ) {
            return $config->$function();
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isDev()
    {
        $uri = $_SERVER[ 'REQUEST_URI' ];
        if ( stristr( $uri, '/dev' ) ) {
            return TRUE;
        }
        elseif ( stristr( $uri, 'app_dev.php' ) ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param bool $group_ie
     *
     * @return mixed|string
     */
    public function getBrowser( $group_ie = FALSE )
    {
        $server = $_SERVER;
        $agent  = isset( $server[ 'HTTP_USER_AGENT' ] ) ? $server[ 'HTTP_USER_AGENT' ] : 'chrome';
        if ( stristr( $agent, 'chrome' ) ) {
            return 'chrome';
        }
        else if ( stristr( $agent, 'msie' ) ) {
            if ( !$group_ie ) {
                for ( $i = 6; $i <= 12; $i++ ) {
                    $test = 'msie ' . $i;
                    if ( stristr( $agent, $test ) ) {
                        return str_replace( ' ', '', $test );
                    }
                }
            }

            return 'ie';
        }
        else if ( stristr( $agent, 'opera' ) ) {
            return 'opera';
        }
        else if ( stristr( $agent, 'firefox' ) ) {
            return 'firefox';
        }

        return 'chrome';
    }

    /**
     * @param array $array
     *
     * @return null
     */
    public function getRandomFromArray( $array = array() )
    {
        if ( !is_array( $array ) ) {
            $array = array();
        }
        shuffle( $array );
        foreach ( $array as $item ) {
            return $item;
        }

        return NULL;
    }

    /**
     * @param      $string
     * @param null $search
     *
     * @return bool|string
     */
    public function getContains( $string, $search = NULL )
    {
        if ( $search === NULL ) {
            return TRUE;
        }

        return strstr( $string, $search );
    }

    /**
     * @param null $string
     *
     * @return string
     */
    public function getUcWords( $string = NULL )
    {
        return ucwords( $string );
    }

    /**
     * @param      $string
     * @param int  $start
     * @param null $length
     * @param bool $dots
     *
     * @return string
     */
    public function getSubstr( $string, $start = 0, $length = NULL, $dots = TRUE )
    {
        if ( empty( $length ) ) {
            return $string;
        }

        $length = intval( $length );
        $state  = intval( $start );

        if ( strlen( $string ) > $length && $dots ) {
            $dots = '...';
        }
        else {
            $dots = '';
        }

        return substr( $string, $start, $length ) . $dots;
    }

    /**
     * @param      $format
     * @param null $days
     * @param null $months
     * @param null $years
     *
     * @return string
     */
    public function getFutureDate( $format, $days = NULL, $months = NULL, $years = NULL )
    {
        $day   = date( 'd' );
        $month = date( 'n' );
        $year  = date( 'Y' );
        if ( !empty( $days ) ) {
            $day += intval( $days );
        }
        if ( !empty( $months ) ) {
            $month += intval( $months );
        }
        if ( !empty( $years ) ) {
            $year += intval( $years );
        }
        $time = mktime( 0, 0, 0, $month, $day, $year );

        return date( $format, $time );
    }

    /**
     * @param $format
     *
     * @return string
     */
    public function getDate( $format )
    {
        return date( $format );
    }
}