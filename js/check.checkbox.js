function checkBoxAction(obj, chkCount, pageUrl, mod)
{  if(!checkmode(mod)) {
	return false;
	}
    var values = '';
	var isSelected = false;
	for(var i=0; i<chkCount; i++)
	{
		var temp = document.getElementById("chk" + i);
		
		if(temp.checked)
		{
			values = values + temp.value + ",";
			isSelected = true;
			
		}
	}
	console.log(values);
	if(isSelected != true)
		return false;
	else
	{
		values = values.substr(0, values.length-1);
		
		document.location = pageUrl + values;
	}
	

}

function selectDeAll(obj, chkCount, val)
{ 
	var chkController = document.getElementById("checkbox");
  
	if(val == 1)
	{ 
		for(var i=0; i<chkCount; i++)
		{
			var temp = document.getElementById("chk" + i);
			
			if(chkController.checked)
			{
				temp.checked = true;
			}
			else
			{
				temp.checked = false;
			}
		}
	}
	
	
	
	if(val == 2)
	{
		/// Code for checking uncheck
		var count = 0;

		for(var i=0; i<chkCount; i++)
		{	
			var temp = document.getElementById("chk"+i);
			
			if(temp.checked)
			{
				chkController.checked = true;
				count = 1;
			}
			else if(!temp.checked && count == 1)
			{
				chkController.checked = true;
			}
			else
			{
				chkController.checked = false;
			}
		}
	}
	
}


function checkAll(obj, chkCount)
{
	var chkController = document.getElementById("checkbox");
	chkController.checked = true;
	for(var i=0; i<chkCount; i++)
	{
		var temp = document.getElementById("chk"+i);
		temp.checked = true;
	}
}



function uncheckAll(obj, chkCount)
{
	var chkController = document.getElementById("checkbox");
	chkController.checked = false;
	for(var i=0; i<chkCount; i++)
	{
		var temp = document.getElementById("chk"+i);
		temp.checked = false;
	}
}
function checkmode(mode, location)
{ 
if(mode == 0)
	{ return true; }
if(mode == 1)
	{
		var reply = confirm("Are you sure to delete the record(s) permanently?");
		if(reply == true)
		{
		   return true;
		}
		return false;
	}
if(mode == 2)
	{
		var reply = confirm("Are you sure to update the record?");
		if(reply == true)
		{
		   return true;
		}
		return false;
	}
	
	if(mode == 3)
	{
		var reply = confirm("Are you sure to delete the record?");
		if(reply == true)
		{
			return true;
		}
		return false;
	} 
	if(mode == 4)
	{
		var reply = confirm("Are you sure to delete the records?");
		if(reply == true)
		{
			return true;
		}
		return false;
	} 
	
	if(mode == 5)
	{
		var reply = confirm("Are you sure to restore the record?");
		if(reply == true)
		{
			return true;
		}
		return false;
	}
}

