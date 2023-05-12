class CreateView {

  constructor() {
    this.printerdata = $("#printerdata");  
    this.getData();
  }
 
  //get order details
  getData() {
    if(order_id)
    {
        var data = "";
        var base_url = "https://superpilot.in/dev/tutors/web/site/kotresponse";
        var URL = `${base_url}?orderId=${order_id}`;
        $.ajax({                
            url: URL,
            crossDomain: true,
            contentType: "application/x-www-form-urlencoded",
            dataType: 'json',
            cache: false,
            async:false,
            beforeSend: function() {
              loader.setState(1);
            },
            success: function(result) {                        
              if(result) data = result;
            },
            complete: function() {
              loader.setState(0);
            },
        });
        if(data['status']!='0') {
          this.data = data;
          this.runCalculations();
          this.render();
        }
        else 
        {
          $("#error").html("<p>No data found with the give orderId!<p>");
        }
    }
    
  }

  //Run calculations to dsiplay
  runCalculations() {
  if(!this.data)       
      return false;
    var products = this.data['response']['products'];

    for (var i=0; i<products.length; i++) {
      var total_qty = 0;
      var total_price = 0;
      for (var j=0; j<products[i]['list'].length; j++) {
        var item = products[i]['list'][j];
        products[i]['list'][j]['sub_total'] = parseFloat(item['item_price']) * parseInt(item['order_count']);
        total_qty += parseInt(item['order_count']);
        total_price += products[i]['list'][j]['sub_total'];
      }
      products[i]['total_qty'] = total_qty;
      products[i]['total_price'] = total_price;
    }

    this.data['response']['products'] = products;
  }

  //Render data with html contents
  render() {
    var products = this.data['response']['products'];
    for (let category of products){
      this.generateTableData(category);
    }
  }

  //generating table data
  generateTableData(category) {

    var items = category['list'];
    var table = document.createElement("table");   
    table.append(this.generateTableHeader());
    for (let item of items){
      var tr = document.createElement("tr");      
      tr.append(this.generateData(item['titlewithqty']));
      tr.append(this.generateData(`#${item['order_count']}`));
      tr.append(this.generateData(`Rs.${item['sub_total']}/-`));
      table.append(tr);
    }
   /* */


	var tfoot = document.createElement("tfoot");
	tfoot.setAttribute('style','border-top:2px solid #000 !important');
	tfoot.append(this.generateData(`Totals - ${items.length}`));
    tfoot.append(this.generateData(`#${category.total_qty}`));
    tfoot.append(this.generateData(`Rs.${category.total_price}/-`));

    table.append(tfoot);
    this.generateDiv(category, table);

  }

  //generating table header data
  generateTableHeader() {

    var thead = document.createElement("thead");
	thead.setAttribute('style','margin-bottom :10px');
	thead.setAttribute('style','border-bottom:2px solid #000 !important');
    var tr = document.createElement("tr");
    var th = document.createElement("th");
    th.setAttribute('scope', 'col');
    th.setAttribute('class', 'description');
    th.innerHTML = "Item Name";
    thead.append(th);
    var th = document.createElement("th");
    th.setAttribute('scope', 'col');
    th.setAttribute('class', 'quantity');
    th.innerHTML = "Qty";
    thead.append(th);
    var th = document.createElement("th");
    th.setAttribute('scope', 'col');
    th.setAttribute('class', 'price');    
    th.innerHTML = "Price";
    thead.append(th);
	

    return thead;
  }

  //Generate td data
  generateData(data) {
    var td = document.createElement("td");
    td.innerHTML = data;
    return td;
  };

  //generate table div
  generateDiv(category, table) {

    var dateTime = this.data['response']['orderDateTime'].split(' ');
    var div = document.createElement("div");  
    div.setAttribute('class', 'ticket');

console.log(category['printerRealName']);
console.log(getHash(category['printerRealName']));

    div.setAttribute('id', `div${getHash(category['foodCategoryName'])}`);
    div.setAttribute('printer', `${category['printerRealName']}`);
    div.setAttribute('paper_size', `${category['paperSize']}`);
    var div2 = document.createElement('div');
    var mainHeader = `<div>
                        <h4 styel="font-weight:bold !important;font-size:16px !important">${this.data['response']['storeName']}</h4>
                        <p class="centered" style="margin-bottom:8px;font-weight:bold">${this.data['response']['location']}, ${this.data['response']['address']}</p>
                        <p class="centered" style="font-weight:bold">${this.data['response']['city']}, ${this.data['response']['state']}</p>
                        <p style="display:none;font-weight:bold">${category['foodCategoryName']}</p>
                     </div>`;

    var sectionHeader = `${mainHeader}<div class="SpanContent">      
<p style="font-weight:bold;width:100%;text-align:center;margin-bottom:10px">
  <span style="width: 100%;display:flex">
  	<span style="text-align:left;width:100%;">KOT NO : ${this.data['response']['KOTNo']}</span>
  	<span style="text-align:right;width:100%">KOT Slip No : ${category['KOTSlipNo']}</span>
</span>
</p>
<p style="width:100%;text-align:center;margin-bottom:10px" class="date">
 <span style="width: 100%;display:flex">
	<span style="width:100%;text-align:left">Date : ${dateTime[0]}</span>&nbsp;&nbsp;&nbsp;&nbsp;
	<span style="text-align:right;width:100%;">Time : ${dateTime[1]}</span>
  </span>
</p>
<p style="width:100%;text-align:center;margin-bottom:10px" class="date">
  <span style="width: 100%;display:flex">
  	<span style="text-align:left;width:100% ;">Processed By : ${this.data['response']['processedBy']['designation']}</span>
	<span style="text-align:right;width:100%;">BillNumber : ${this.data['response']['billNumber']}</span>
   </span>
</p>
<p style="width:100%;text-align:center;margin-bottom:10px">
  <span style="width: 100%;display:flex">
	<span style="text-align:left;width:100%;">Customer : ${this.data['response']['customerInfo']['name']}</span>
	<span style="text-align:right;width:100%">Spot Name :<br> ${this.data['response']['tableName']}</span>
  </span>
</p>
<br>
    </div>`;


    div2.innerHTML = sectionHeader;
    div.append(div2);
    div.append(table);
    this.printerdata.append(div);
  }
}