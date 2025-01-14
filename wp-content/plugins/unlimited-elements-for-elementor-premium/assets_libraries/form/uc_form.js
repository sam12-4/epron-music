"use strict";

//version: 1.3

function UnlimitedElementsForm(){
  
  var t = this;
  
  //selectors
  var ueInputFieldSelector, ueNumberSelector, ueNumberErrorSelector, ueOptionFieldSelector;
  
  //objects
  var g_objCalcInputs;
  
  //helpers
  var g_allowedSymbols;
  
  /**
  * trace
  */
  function trace(str){
    console.log(str);
  }
  
  /**
  * show custom error
  */
  function showCustomError(objError, errorText, consoleErrorText){
    
    objError.text(errorText);
    
    objError.show();

    var objErrorParent = objError.parents(".debug-wrapper");
    
    if(!objErrorParent.length)
    throw new Error(consoleErrorText);    

    objErrorParent.addClass("ue_error_true");

    throw new Error(consoleErrorText); 
    
  }
  
  /**
  * get formula names
  */
  function getFormulaNames(expr, objError){
    
    var regex = /\[(.*?)\]/g;
    var matches = expr.match(regex);   
    
    var names;    
    
    if(matches)      
    names = matches.map(match => match.substring(1, match.length - 1));     
    
    var unmatches = expr.replace(regex, "").split(/[\[\]]/);
    
    //exclude allowed symbols and check if array is empty, if not - then in formula some fields written without square parentacess
    unmatches = unmatches[0].replace(g_allowedSymbols, "").split(/[\[\]]/);
    
    if(unmatches[0].length > 0){
      
      var errorText = 'Unlimited Elements Form Error: Input Name should be surrounded by square parentheses inside Formula';
      var consoleErrorText = "Missing square parentheses inside Formula";
      
      showCustomError(objError, errorText, consoleErrorText);
      
    }    
    
    return(names);
    
  }
  
  /**
  * replace fields name with its values
  */
  function replaceNamesWithValues(expr, objError){
    
    var names = getFormulaNames(expr, objError);
    
    names.forEach(function(name, index){
      
      var objInpput = jQuery(ueInputFieldSelector+'[name="'+name+'"]');
      
      if(!objInpput.length){
        
        var errorText = 'Unlimited Elements Form Error: couldn"t find Number Field Widget with name: '+name;
        var consoleErrorText = "Invalid Number Field Widget Name";
        
        showCustomError(objError, errorText, consoleErrorText);
        
      }
      
      if(objInpput.length > 1){
        
        var errorText = 'Unlimited Elements Form Error: Name option must be unique. Found '+objInpput.length+' Number Field Widgets with name: '+name;
        var consoleErrorText = "Invalid Number Field Widget Name";
        
        showCustomError(objError, errorText, consoleErrorText);
        
      }
      
      var inputValue = objInpput.val();
      
      //add parentheses if valus is less then 0
      if(inputValue < 0)
      inputValue = "("+inputValue+")"
      
      expr = expr.replace(name, inputValue);
      expr = expr.replace('[', '');
      expr = expr.replace(']', '');
      
    });
    
    return(expr);
    
  }
  
  /*
  * validate the expression
  */
  function validateExpression(expr){      
    
    //allow Math.something (math js operation), numbers, float numbers, math operators, dots, comas    
    var matches = expr.match(g_allowedSymbols);
    
    var result = "";
    
    if (matches) 
    result = matches.join('');    
    
    expr = result;
    
    return(expr);
    
  }
  
  /**
  * get result from expression
  */
  function getResult(expr, objError) {
    
    //if space just erase it
    expr = expr.replace(/\s+/g, "");
    
    //replace inputs name with its values
    expr = replaceNamesWithValues(expr, objError);
    
    //validate espression
    expr = validateExpression(expr);
    
    var result;
    
    var errorText = `Unlimited Elements Form Error: wrong math operation: ${expr}`;
    var consoleErrorText = `Invalid operation: ${expr}`;
    
    //catch math operation error
    try{
      result = eval(expr);
    }
    
    catch{      
      
      showCustomError(objError, errorText, consoleErrorText);
      
    }
    
    if(isNaN(result) == true){
      
      showCustomError(objError, errorText, consoleErrorText);
      
    }
    
    return result;
    
  }
  
  /**
  * format result number
  */
  function formatResultNumber(result, objCalcInput){
    
    var dataFormat = objCalcInput.data("format");
    
    if(dataFormat == "round")
    return(Math.round(result))
    
    if(dataFormat == "floor")
    return(Math.floor(result))
    
    if(dataFormat == "ceil")
    return(Math.ceil(result))
    
    if(dataFormat == "fractional"){
      
      var dataCharNum = objCalcInput.data("char-num");
      
      return(result.toFixed(dataCharNum))
      
    }
    
  }
  
  /**
  * init calc mode
  */
  function setResult(objCalcInput, objError){
    
    //if data formula is empty
    var dataFormula = objCalcInput.data("formula");
    
    if(dataFormula == "" || dataFormula == undefined)
    return(false);

    //get result with numbers instead of fields name
    var result = getResult(dataFormula, objError);

    //format result
    result = formatResultNumber(result, objCalcInput);
    
    //set result to input
    objCalcInput.val(result);
    
    //set readonly attr
    objCalcInput.attr('readonly', '');
    
  }
  
  /**
  * input change controll
  */
  function onInputChange(objCalcInput){
    
    objCalcInput.trigger("input_calc");
    
  }
  
  /**
  * assign parent calc number field input to each input inside formula
  */
  function assignParentNumberField(objParent, objError){
    
    var objFormula = objParent.find("[data-formula]");
    var expr = objFormula.data("formula");
    var parentIdAttribute = objParent.attr("id");
    
    var names = getFormulaNames(expr, objError);
    
    names.forEach(function(name, index){
      
      var objInpput = jQuery(ueInputFieldSelector+'[name="'+name+'"]');
      
      objInpput.attr("data-parent-formula-input", parentIdAttribute);      
      
    });
    
  }
  
  /**
  * get parent input calc
  */
  function getParentCalcInput(objInput){
    
    var parentAttr = objInput.data("parent-formula-input");
    
    if(!parentAttr)
    return(null); 
    
    var objParentCalkInput = jQuery("#"+parentAttr).find("[data-calc-mode='true']");
    
    return(objParentCalkInput);
    
  }
  
  /**
  * show main input
  */
  function showField(objFieldWidget, classHidden){
   
    objFieldWidget.removeClass(classHidden);
    
  }
  
  /**
  * hide main input
  */
  function hideField(objFieldWidget, classHidden){
  
    objFieldWidget.addClass(classHidden);
    
  }
  
  /**
  * get condition
  */
  function getConditions(visibilityCondition, condition, objFieldValue, fieldValue){
     
    switch (condition) {
      case "=":
      
      visibilityCondition = objFieldValue == fieldValue;
      
      break;
      case ">":
      
      visibilityCondition = objFieldValue > fieldValue;
			
      break;
      case ">=":
      
      visibilityCondition = objFieldValue >= fieldValue;
      
      break;
      case "<":
      
      visibilityCondition = objFieldValue < fieldValue;
      
      break;
      case "<=":
      
      visibilityCondition = objFieldValue <= fieldValue;
      
      break;
      case "!=":
      
      visibilityCondition = objFieldValue != fieldValue;
      
      break;
      
    }
    
    return(visibilityCondition);
    
  }
  
  /**
  * get operator
  */
  function getOperators(operator, visibilityOperator){
    
    switch (operator){
      
      case "and":
      
      visibilityOperator = "&&";
      
      break;
      case "or":
      
      visibilityOperator = "||";
      
      break;
      
    }   
    
    return(visibilityOperator);
    
  }
  
  
  /**
  * get names
  */
  function getNames(arrNames, fieldName){
    
    arrNames = [];
    arrNames.push(fieldName);
    
    return(arrNames);
    
  }
  
  /**
  * equal condition and input names error
  */
  function equalConditionInputNameError(objField, arrNames, classError){
    
    var inputName = objField.attr("name");
    
    var isNamesEqual = arrNames.indexOf(inputName) != -1;
   
    if(isNamesEqual == true){
      
      var errorHtml = "<div class="+classError+">Unlimited Field Error: can't set condition. Condition Item Name equals Field Name: [ " + inputName + " ]. Please use different names.</div>";
      
      jQuery(errorHtml).insertBefore(objField.parent());
      
    }
    
  }
  
  /**
  * set visibility in editor
  */
  function setVisibilityInEditor(objFieldWidget, classError){    
    
    var hiddenHtml = "<div class="+classError+">Unlimited Field is hidden due to Visibility Condition Options. <br> This message shows only in editor.</div>";
    
    objFieldWidget.html(hiddenHtml);
    
  }
  
  /**
  * check if calculator input includes invisible inputs
  */
  function checkInvisibleInputsInFormula(){
    
    //if no calc mode inpu found on page - do nothing
    if(!g_objCalcInputs.length)
    return(false);			
    
    //look after each calc mode input field on a page
    g_objCalcInputs.each(function(){
      
      var objCalcInput = jQuery(this);
      
      //find main warapper of the widget
      var objCalcWidget = objCalcInput.parents(ueNumberSelector);		
      var objError = objCalcWidget.find(ueNumberErrorSelector);
      var formula = objCalcInput.data('formula');
      
      if(!formula)
      return(true);
      
      var names = getFormulaNames(formula, objError);
      
      names.forEach(function(name, index){
        
        var objInpput = jQuery(ueInputFieldSelector+'[name="'+name+'"]');
        
        //check if field is hidden due to condition
        if(objInpput.is(':visible') == false){
          
          var errorText = 'Unlimited Elements Form Error: Field is invisible on the page, but contains in formula: '+name+'.';
          var consoleErrorText = `Field is invisible on the page, but contains in formula: ${name}`;
          
          showCustomError(objError, errorText, consoleErrorText);
          
        }
        
      });
      
    });    
    
  }
  
  /*
  * process the visibility array
  */
  t.setVisibility = function(conditionArray, widgetId){	  
    
    var objFieldWidget = jQuery("#"+widgetId);
    var classHidden = "ucform-has-conditions";
    var classError = "ue-error";
     
    var conditions = conditionArray.visibility_conditions;
    var conditionsNum = conditions.length;
    
    if(conditionsNum == 0)
    return(false);
    
    var totalVisibilityCondition;
    
    //create val to contain all the names for errors catching purposes
    var arrNames;
    
    for(let i=0; i<conditionsNum; i++){
      
      var conditionArray = conditions[i];
      var condition = conditionArray.condition;
      var fieldName = conditionArray.field_name;
      var fieldValue = parseInt(conditionArray.field_value);
      var operator = conditionArray.operator;
      var id = conditionArray._id;
      
      var objField = jQuery(ueInputFieldSelector+'[name="'+fieldName+'"]');
      var objFieldValue = parseInt(objField.val());
    
      //sets the condition: "==", ">", "<" ...
      var visibilityCondition = getConditions(visibilityCondition, condition, objFieldValue, fieldValue);
      
      //set the conditions: "&&", "||"
      var visibilityOperator = getOperators(operator, visibilityOperator);             
      
      //if only one item exist - ignore the condition ("&&", "||")
      if(i == 0)
      totalVisibilityCondition = visibilityCondition;
      
      if(i > 0)
      totalVisibilityCondition += visibilityOperator + visibilityCondition;      
      
      //show error if condition name equals input field name
      arrNames = getNames(arrNames, fieldName);
      
	  var objInputField = objFieldWidget.find(ueInputFieldSelector);
		
      equalConditionInputNameError(objInputField, arrNames, classError);
      
    }
    
    if(eval(totalVisibilityCondition) == true)
    showField(objFieldWidget, classHidden);
    
    if(eval(totalVisibilityCondition) == false){
      
      var isInEditor = objField.data("editor");
      
      if(isInEditor == "yes")
      setVisibilityInEditor(objFieldWidget, classError);
      else
      hideField(objFieldWidget, classHidden);
      
    }
    
    //check if in formula exists invisible field
    checkInvisibleInputsInFormula();
    
  }
  
  /**
  * init the form
  */
  t.init = function(){
    
    //if no calc mode inpu found on page - do nothing
    if(!g_objCalcInputs.length)
    return(false);
    
    //look after each calc mode input field on a page
    g_objCalcInputs.each(function(){
      
      var objCalcInput = jQuery(this);
      
      //find main warapper of the widget
      var objCalcWidget = objCalcInput.parents(ueNumberSelector);		
      var objError = objCalcWidget.find(ueNumberErrorSelector);
      
      //assign parent calc input number field widget for each ue field input that is inside formule of the number filed
      assignParentNumberField(objCalcWidget, objError);
      
      //set result in input
      setResult(objCalcInput, objError);    
      
      //init events
      var objAllInputFields = jQuery(ueInputFieldSelector);
      
      //on input change trigger only parent calc number field, not all of them
      objAllInputFields.on('input', function(){
        
        var objInput = jQuery(this); //triggered input
        var objParentCalkInput = getParentCalcInput(objInput); //parent calc input with formula attr
        
        if(objParentCalkInput == null)
        return(false);
        
        onInputChange(objParentCalkInput);
        
      });
      
      //set result on custom shange event
      objCalcInput.on('input_calc', function(){
        
        var objInput = jQuery(this); //triggered input
  
        setResult(objInput, objError);
        
      });
      
    });
    
    
    
    
  }
  
  /**
  * init vars
  */
  function initVars(){
    
    //selector
    ueInputFieldSelector = ".ue-input-field";
    ueNumberSelector = ".ue-number";
    ueNumberErrorSelector = ".ue-number-error";
    ueOptionFieldSelector = ".ue-option-field";
    
    //objects
    g_objCalcInputs = jQuery(ueInputFieldSelector+'[data-calc-mode="true"]');
    
    //helpers
    g_allowedSymbols = /Math\.[a-zA-Z]+|\d+(?:\.\d+)?|[-+*/().,]+/g;
    
  }
  
  initVars();
  
}

var g_ucUnlimitedForms = new UnlimitedElementsForm();

g_ucUnlimitedForms.init();