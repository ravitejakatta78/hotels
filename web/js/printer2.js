/* JSPrinterManger
   
    1. Customized class to interact with ClientSide (Browsers) connected local printers
    2. Options - Default, Installed, UserSelected printers

*/
//WebSocket settings
JSPM.JSPrintManager.auto_reconnect = true;
JSPM.JSPrintManager.start();
JSPM.JSPrintManager.WS.onStatusChanged = function () {

    let printers = new Printers();

    if (printers.jspmWSStatus()) {

        //get client installed printers
        JSPM.JSPrintManager.getPrintersInfo().then(function (printersList) {

            printers.loadPrinters(printersList);
            printers.loadPaperSizes(printersList);
            printers.selectedPaperSize();
            JSPMConfig.printerDropdownNode.change(function() {
                printers.loadPaperSizes(printersList);
            });
            if(typeof CreateView == 'function') {
                let view = new CreateView();
                printers.printFromHTML();
            }
        });
    }
};

//Printer wrapper
class Printers {

    constructor() {
        this.virtual_printers = {};
        this.real_printers = {};

        //All Printers
        this.clients = {
            'virtual': {},
            'real': {}
        };

        //List of JOBS
        this.jobs = [];
    }


    //Check JSPM WebSocket status
    jspmWSStatus() {

        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
            return true;
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
            alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
            alert('JSPM has blocked this website!');
            return false;
        }
    }


    //Guessing whether a printer is Virtual or real/physical
    isVirtualPrinter(clientPrinter) {
        let printerPort = clientPrinter.port.toLowerCase();
       
        //For Windows
        if (printerPort != "nul" && clientPrinter.BIDIEnabled) return false;

        //For Unix
        if (printerPort.indexOf("usb") >= 0 && printerPort.indexOf("?serial=") >= 0) return false;
           
        return true;
    }


    //Load all the available printers
    loadPrinters(printersList) {

        //Build Options List
        let options = '';

        for (let i = 0; i < printersList.length; i++) {

            if(this.isVirtualPrinter(printersList[i])) {
               this.clients['virtual'][printersList[i].name] = printersList[i];

                if(JSPMConfig.allowVirtual)
                    options += `<option value='${printersList[i].name}' optionId='${i}'>${printersList[i].name} - Virtual Printer</option>`;
           
            }else {
               this.clients['real'][printersList[i].name] = printersList[i];
                options += `<option value='${printersList[i].name}' optionId='${i}'>${printersList[i].name} - Real Printer</option>`;
            }
        }

        this.loadPrintersDropdown(options);
    }

     //Load all the available paper sizes
    loadPaperSizes(printers) {

        //Build Options List
        let printerId = JSPMConfig['printerDropdownNode'].find('option:selected').attr('optionId');
        let printer = printers[printerId];
        let options = '<option>--Choose Paper Size--</option>';
        if(!printer) return false;

        for (var i = 0; i < printer.papers.length; i++) {
            options += `<option value='${printer.papers[i]}'>${printer.papers[i]}</option>`;
        }

        JSPMConfig['printerPapers'].html(options);
    }


    //Append available Real/Physical printers in dropdown
    loadPrintersDropdown(options) {

        //JSPMConfig.printButton.click(initPrintings);
        if(options) {
            JSPMConfig.printerDropdownNode.html(options);
            this.selectedPrinters();
            this.selectedPaperSize();
        }else {
            JSPMConfig.printerDropdownNode.html("<option>No Physical printers found!</option>");
        }
    }

    //Select available Real/Physical printers in dropdown
    selectedPrinters(printer_name=null) {
        if(JSPMConfig.preSelected.length)
        {
           var printer_name = JSPMConfig.preSelected.val();
           if(printer_name) JSPMConfig.printerDropdownNode.val(printer_name);
        }
    }

     //Select available Real/Physical printers in dropdown
    selectedPaperSize(paper_size=null) {
        if(JSPMConfig.preSelectedPaperSize.length)
        {
           var paper_size = JSPMConfig.preSelectedPaperSize.val();
           if(paper_size) JSPMConfig.printerPapers.val(paper_size);
        }
    }


    //PrintFromHTML
    printFromHTML() {
        var printerData = document.getElementById("printerdata").children;

       /* if(printerData.length) {
            loader.setState(1);
            myInterval = setInterval(SetTimeOut, 1000);
        }*/
       
        for(var i=0; i<printerData.length; i++) {

            loader.setMessage("Printing inprogress....");
            this.printNow(printerData, i);
        }
        alert("Printers triggered with data!");
        return false;
    }

    printNow(printerData, i) {       
        var printerName = printerData[i].getAttribute('printer');
        var paperSize   = printerData[i].getAttribute('paper_size');
        var CategoryId  = printerData[i].getAttribute('id');

        html2canvas(document.getElementById(CategoryId), { scale: 5 }).then(function (canvas) {

            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer type
            //printerName = 'Microsoft Print to PDF';
            if (printerName && printerName!="null") {
                cpj.clientPrinter = new JSPM.InstalledPrinter(printerName);
            } else {
                cpj.clientPrinter = new JSPM.DefaultPrinter();
            }
            cpj.paperName = paperSize;

            //Add jobCount + 1
            jobCount = jobCount + 1;

            //Set content to print...
            var b64Prefix = "data:image/png;base64,";
            var imgBase64DataUri = canvas.toDataURL("image/png");
            var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);
            var myImageFile = new JSPM.PrintFile(imgBase64Content, JSPM.FileSourceType.Base64, 'myFileToPrint.png', 1);

            //add file to ClientPrintJob
            cpj.files.push(myImageFile);

            //Handle print job events
            cpj.onUpdated = function (data) {
                //$("#txtPrintJobTrace").val($("#txtPrintJobTrace").val() + "> " + JSON.stringify(data) + "\r\n");
                //console.log(data);
                if(data.hasOwnProperty('result')) jobCount = jobCount - 1;
                else if(data.hasOwnProperty('id') && data['id']==-1) jobCount = jobCount - 1;
            };

            //On finishing the print event
            cpj.onFinished = function (data) {
                //$("#txtPrintJobTrace").val($("#txtPrintJobTrace").val() + "> " + JSON.stringify(data) + "\r\n");
                //console.info(data);
                //jobCount = jobCount - 1;
            };
            cpj.sendToClient();
        });
    }
}

//Creating HTML view category wise
/**/

//Helper functions
function getHash(input) {
  input = input.replaceAll(' ','');
  var hash = 0, len = input.length;
  for (var i = 0; i < len; i++) {
    hash  = ((hash << 5) - hash) + input.charCodeAt(i);
    hash |= 0; // to 32bit integer
  }
  return hash;
}

function SetTimeOut() {
    console.log(jobCount);
    if (jobCount==0) {    
        loader.setState(0);
        StopTimeOut();
    }
}

function StopTimeOut(){
    clearInterval(myInterval);
};
