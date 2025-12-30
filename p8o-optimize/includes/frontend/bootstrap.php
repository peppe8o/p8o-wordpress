<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Feature implementations (frontend hooks live inside these modules).
require_once dirname( __DIR__ ) . '/features/css.php';
require_once dirname( __DIR__ ) . '/features/js.php';
require_once dirname( __DIR__ ) . '/features/images.php';
require_once dirname( __DIR__ ) . '/features/external-js-hints.php';
require_once dirname( __DIR__ ) . '/features/ads-cls.php';
require_once dirname( __DIR__ ) . '/helpers.php';