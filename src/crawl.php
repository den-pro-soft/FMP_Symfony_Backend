<?php
$servername = "findmyprofession.com";
$username = "star";
$password = "starstars";
$dbname = "mike_real";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$get_url = $_REQUEST['q'];

$url_array = explode("/", $get_url);


if( $url_array[0] == "career-advice" )
{
    $sql = "SELECT seo_title, description, image_name FROM blog where url = '".$url_array[1]."'";
}else{
        if( $url_array[0] == "sitemap.xml" )
  {
        $file = file_get_contents('/home/ubuntu/angular/dist/sitemap.xml');
        header("Content-type: text/xml");
        echo $file;
        exit();
  } elseif ( $url_array[0] == "robots.txt" ) {
    $robots_file = file_get_contents('/home/ubuntu/angular/dist/robots.txt');
    echo $robots_file;
    exit();
} else{
     if( $url_array[0] == "" )
        $sql = "SELECT seo_title, description, image_name FROM pages where slug = 'find-profession-best-career-advice-career-finder'";
      else
        $sql = "SELECT seo_title, description, image_name FROM pages where slug = '".$url_array[0]."'";
  }
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $seo_title      = $row["seo_title"];
        $description    = $row["description"];
        $image_name     = $row["image_name"];
        $image_name = "https://www.findmyprofession.com:8443/uploads/".$image_name;
        //$image_name = "https://concepter.co/wp-content/themes/iblazr/solutions/dist/img/main_sharing.jpg";
    }

}


ob_start();

echo '<html lang="en">
<head>
  <meta charset="UTF-8">
  <base href="/">
  <title>FMP</title>
  <meta name="robots" content="noodp, noydir">
  <meta http-equiv="Content-language" content="en">
  <meta name="title" >
  <meta name="description" >

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">

  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:description"/>
  <meta name="twitter:title" />
  <meta name="twitter:image" >


  <meta property="og:locale" content="en_US"/>
  <meta property="og:type" content="website"/>
  <meta property="og:title" />
  <meta property="og:url" content="https://www.findmyprofession.com"/>
  <meta property="og:site_name" content="Find My Profession"/>
  <meta property="og:description" />
  <meta property="og:image" >
  <meta property="og:updated_time" content="2017-09-12T13:19:06-04:00"/>

  <meta name=“google-site-verification” content=“iVNXjQIcEKufABID-dc-kEVBSamCA75QPb-AcO5CmUM” />

  <!--Meta tags for socials-->
  <link rel="icon" type="image/vnd.microsoft.icon" href="favicon_32.png">
  <link rel="apple-touch-icon" href="favicon_152.png">
</head>
<body>
  <fmp-app class="wrapper-app"></fmp-app>
  <script>
    (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,"script","https://www.google-analytics.com/analytics.js","ga");

    ga("create", "UA-72462215-1", "auto");  // <- add the UA-ID from your tracking code
                                           // <- remove the last line like me
  </script>
</body>
</html>
';

$pageTitle='FMP';
$pg=ob_get_contents();

//delete old desc and author
$pg = str_replace('content=""', '', $pg);
//add anew desc and author
$pg = str_replace('name="twitter:description"', 'name="twitter:description" content="'.$description.'" ', $pg);
$pg = str_replace('name="twitter:title"', 'name="twitter:title" content="'.$seo_title.'" ', $pg);
$pg = str_replace('name="twitter:image"', 'name="twitter:image" content="'.$image_name.'" ', $pg);

$pg = str_replace('name="title"', 'name="title" content="'.$seo_title.'" ', $pg);
$pg = str_replace('name="description"', 'name="description" content="'.$description.'" ', $pg);
$pg = str_replace('property="og:title"', 'property="og:title" content="'.$seo_title.'" ', $pg);
$pg = str_replace('property="og:description"', 'property="og:description" content="'.$description.'" ', $pg);
$pg = str_replace('property="og:image"', 'property="og:image" content="'.$image_name.'" ', $pg);

ob_end_clean();
echo str_replace('<!--TITLE-->',$pageTitle,$pg);

mysqli_close($conn);
?>

