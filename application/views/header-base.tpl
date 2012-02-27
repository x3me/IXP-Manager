<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{genUrl}/index.php">

    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta charset="utf-8">
    
    <title>{$pageTitle|default:"IXP Manager"}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    

	{if $config.use_minified_css}
	    <link rel="stylesheet" type="text/css" href="{genUrl}/css/min.bundle-v1.css">
    {else}
        {include file="header-css.tpl"}
	{/if}

	{if $config.use_minified_js}
    	<script type="text/javascript" src="{genUrl}/js/min.bundle-v1.js"></script>
    {else}
        {include file="header-js.tpl"}
	{/if}
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    {if ( not isset( $hasIdentity ) or not $hasIdentity or $user.privs neq 3 ) and ( not isset( $mode ) or $mode neq 'fluid' )}
        <style>
            html, body {
              background-color: #eee;
            }
            
            body {
                padding-top: 40px;
            }
        </style>
    {/if}
    
</head>

<body>




