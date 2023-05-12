<head>
  <meta charset="UTF-8">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
  <title>Receipt example</title>                           
</head>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');
</style>

<style>
#printerdata
  {
    box-shadow: 0 0 1in -0.5in rgb(0 0 0 / 50%);
    padding: 10px;
    margin:auto;
    color:#000;
    width: 22%!important;
    background: #FFF;
  }
td,
th,
tr,
thead,
tfoot,
table {
    border: none!important;
    border-collapse: collapse;
    margin-left: 8px;
  text-align: center;
  margin:auto;
  line-height: 28px;
  border:1px solid #000 !important;

}

  table {
    width:100%!important;
     border:1px solid #000 !important;
  }

td.description,
th.description {
    width:50%!important;
    max-width:50%!important;
}
  td,th{
     border:1px solid #000 !important;
  }

td.quantity,
th.quantity {
    word-break: break-all;
  text-align:center;
}

td.price,
th.price {
    word-break: break-all;
      text-align: center;
}

.centered {
    text-align: center;
    align-content: center;
}
.ticket{
  
}
  

/*  *{
    font-size:  12px !important;
    font-family: 'Roboto', sans-serif; 
    font-weight: 
} */
  
*{
  font-family:'Roboto', sans-serif; 
  font-style: normal; 
  font-size:17px!important;
  line-height: 20px;
  color:#000;
};   
  
</style>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<body>
<div style="text-align:center">
    <h1 style="margin-bottom:3%;">Ordery Summmary <span id="order_id">#</span></h1>

    <!-- <button id="print_button">Print >></button> -->

    <div id="printerdata">      

  </div>
</div>
    
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="<?= Yii::getAlias('@abspath') ?>/js/scripts/JSPrintManager.js"></script>
<script src="<?= Yii::getAlias('@abspath') ?>/js/createView.js"></script>
<script src="<?= Yii::getAlias('@abspath') ?>/js/printer2.js"></script>
<script src="<?= Yii::getAlias('@abspath') ?>/js/loader.js"></script>
<script type="text/javascript">
    var JSPMConfig = {
        'printerDropdownNode': $("#printers"),
        'printerPapers': $("#printer_papers"),
        'preSelected': $("#pre_select_printer"),
        'preSelectedPaperSize': $("#pre_select_paper_size"),
        'printButton': $("#print_button"),
        'allowVirtual': true,       
    };
    let order_id = "<?= $order_id; ?>";
    let loader = new Loader();
    let jobCount = 0;
    let myInterval;
    let printerName_alias="";
</script>