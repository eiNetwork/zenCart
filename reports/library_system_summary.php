<?php

require('../includes/application_top.php');

// Need to determine the library system id from the logged on user.
// We are making the assumption that each user will be related to one and only one library system
// Hard coded right now for testing
$librarySystemID = 2;

//These are the parameters from the query.  Hard coded right now for testing.
$startDate = "2016-12-23";
$endDate = "2016-12-24";

// this is the query itself
$sqlQuery = "select ab.entry_company, cd.categories_name, p.products_model, pc.config_description,
count(o.orders_id) as NbrOrders,
sum(op.products_quantity) as Quantity, sum(op.final_price) as UnitPrice,
sum(op.final_price * op.products_quantity) as TotalPrice,
from orders as o
join orders_products op on op.orders_id = o.orders_id
join products p on p.products_id = op.products_id
left outer join products_config pc on op.products_prid = pc.products_prid
join categories_description cd on cd.categories_id = p.master_categories_id
join address_book ab on ab.address_book_id = o.delivery_address_id
and ab.library_system_id = " . $librarySystemID . " and o.date_purchased &gt;= " . $startDate . " and o.date_purchased &lt;= " .  $endDate .
"group by ab.entry_company, cd.categories_name, p.products_model, pc.config_description, pc.vendor_config_id
order by ab.entry_company, cd.categories_name, p.products_model, pc.config_description, pc.vendor_config_id";

$librarysummary = $db->Execute($sqlQuery);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="../includes/stylesheet.css">
<link rel="stylesheet" type="text/css" media="print" href="../includes/stylesheet_print.css">
</head>
<body>
<h1>Library System Summary</h1>
</body>
</html>

