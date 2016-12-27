$(function() {
	function makeTable(container, data) {
		var table = $("<table id='cartList'/>").addClass('CSS_Cart');
		$.each(data, function(rowIndex, r) {
			var row = $("<tr/>");
			$.each(r, function(colIndex, c) { 
				row.append($("<t"+(rowIndex == 0 ?  "h" : "d")+"/>").text(c));
			});
			table.append(row);
		});
		return container.append(table);
	}
	
	function appendTableColumn(table, rowData, key) {
	  var lastRow = $('<tr key=' + key + '/>').appendTo(table.find('tbody:last'));
	  $.each(rowData, function(colIndex, c) {
		  lastRow.append($('<td/>').html(c));
	  });		   
	  return lastRow;
	}

	function removeTableColumn(table, rowData) {
	  var lastRow = $('<tr/>').appendTo(table.find('tbody:last'));
	  $.each(rowData, function(colIndex, c) {
		  lastRow.append($('<td/>').html(c));
	  });		   
	  return lastRow;
	}	 				
				
	var sItems = getSelectedItems();
	
	$('#cart').text("Krepšelis (" + sItems.length + ")");
		
	var cartClicked = false;
	$("#cart").hover(function() {
		if (cartClicked) { return false; }
		$("#cartContent").empty();
		
		if (sItems.length != sItems.filter(Boolean).length)
			return false;
		
		sItems = getSelectedItems();
		if (sItems.length == 0)
			return false;						
		
		var buyFunction = function() {
			// console.log("Made a purchase!");
			
			// Update session items
			var daiktai = JSON.parse(JSON.stringify(sItems));
			$('#cartList td:nth-child(3)').each(function(index) {
				var key = $($(this)[0].parentElement).attr('key');
				var kiekis = parseInt($(this).text());
				daiktai[key].Kiekis = kiekis;
			});
									
			$.ajax({
				type: "POST",
				url: "include/krepselis.php",
				data: {'func': 'updateSessionItems', 'args': JSON.stringify(daiktai)},
				success: function(response) {
					$.ajax({
						type: "POST",
						url: "include/krepselis.php",
						data: {'func': 'uploadCart', 'args': ''},
						success: function(response) {
							$('<form action="uzsakymas.php" method="POST"><input type="hidden" name="uzsakymoID" value=' + response + ' /></form>').submit();
						}
					});	
				}
			});	
			$(this).attr('disabled', true);
		}
		
		var totalPrice = 0;
		var deleteFunction = function(event) {
			var thisButton = $(this);
			var thisButtonArrayLink = Math.max(0, event.data.key - sItems.filter(String).length);
			$.ajax({
				type: "POST",
				url: "include/krepselis.php",
				data: {'func': 'removeSessionItem', 'args': thisButtonArrayLink},
				success: function(response) {
					// console.log(response);
					
					// Update "Pirkti" button
					totalPrice = totalPrice - parseFloat(sItems[event.data.key].Kaina);
					$("#cartButton").text("Pirkti (" + totalPrice + " €)");
					
					sItems[event.data.key] = null;
					thisButton.closest('tr').remove();
					if (sItems.filter(Boolean).length == 0) {
						$("#cartContent").hide();
						window.location.href = window.location.href;
					}
				}
			});	
		}
		
		
		var headers = [["Prekes Kodas", "Pavadinimas", "Kiekis", "Kaina, €", "Veiksmai"]];
		var table = makeTable($("#cartContent"), headers);
		$(sItems).each(function(key, value) {
			sItems[key].maxKiekis = sItems[key].Kiekis;
			sItems[key].Kiekis = 1;
			totalPrice = totalPrice + parseFloat(sItems[key].Kaina);
			appendTableColumn(table, [sItems[key].prekesKodas, sItems[key].Pavadinimas, sItems[key].Kiekis, sItems[key].Kaina, 
				(function() {
					var b = $('<button>DELETE</button>');
					b.addClass('btn btn-danger navbar-btn');
					b.click({key}, deleteFunction);
					return b;
				})()
			], key);					
		});
		$("#cartContent").append($('<button>Pirkti (' + totalPrice + ' €)</button>').attr('id', 'cartButton'));
		$("#cartButton").addClass('btn btn-danger navbar-btn').wrap("<center></center>");
		$("#cartButton").click(buyFunction);

		//------------
		$("#cartList td:nth-child(3)").click(function (e) {
			e.preventDefault(); // <-- consume event
			e.stopImmediatePropagation();
			
			$this = $(this);
			if ($this.data('editing')) 
				return;  
			
			var val = $this.text();
			
			$this.empty()
			$this.data('editing', true);        
			
			$('<input type="text" class="editfield">').val(val).appendTo($this);
		});

		putOldValueBack = function () {
			$("#cartList .editfield").each(function(){
				// Update fields
				$this = $(this);
				var val = parseInt($this.val());
				var td = $this.closest('td');
				var key = $($(td)[0].parentElement).attr('key');

				if (val == 0) {
					val = 1;
				} else if (val > sItems[key].maxKiekis) {
					val = sItems[key].maxKiekis;
				}
				td.empty().html(val).data('editing', false);					
				
				// Update Total Cost
				totalPrice = 0;
				$('#cartList td:nth-child(3)').each(function(index) {
					var key = $($(this)[0].parentElement).attr('key');
					var kaina = parseFloat(sItems[key].Kaina)
					var kiekis = parseInt($(this).text());
					totalPrice = totalPrice + kaina * kiekis;
				});											
				
				$("#cartButton").text('Pirkti (' + totalPrice + ' €)');	
			});
		}

		$(document).click(function (e) {
			putOldValueBack();
		});
		
		//----------------------------------
		$("#cartContent").fadeIn(200);
		$("#cartContent").show();
		var tooltip = $("> div", this).show();
		var pos = tooltip.offset();
		tooltip.hide();
		var right = pos.left + tooltip.width();
		var pageWidth = $(document).width();
		if (pos.left < 0) {
			tooltip.css("marginLeft", "+=" + (-pos.left) + "px");
		}
		else if (right > pageWidth) {
			tooltip.css("marginLeft", "-=" + (right - pageWidth));
		}
		tooltip.fadeIn();
	}, function() {
		$("> div", this).fadeOut(function() {$(this).css("marginLeft", "");});			
	});
	
	$("#cart").click(function() { cartClicked = !cartClicked; });
	$("#cartContent").hover(function() {}, 
		function() {
			if (!cartClicked) {
				$(this).fadeOut(200);
				if (sItems.length != sItems.filter(Boolean).length) {
					window.location.href = window.location.href;
				}				
			}
		}
	);
});