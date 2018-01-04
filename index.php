<?php
define('ROOT', dirname(__FILE__));

define('PART', 'ship');
$linked = false;
$tempString = 'Your name here';

require_once(ROOT.'/config/config.inc.php');

// Make a random string
function makeRandomString($length){
	$str = array('a', 'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'W', 'X', 'Y', 'Z', '2', '4', '5', '6', '7', '8', '9');
	$nkeys = array_rand($str, $length);
	$nstr = '';
	foreach($nkeys as $key){ $nstr .= $str[$key]; }
	return $nstr;
}

 // Make sure a url string is nicely formatted
function makeSafeUrl($myUrl, $allowSpace=0, $allowCase=0, $allowDot=0){
	$sSafe = 'abcdefghijklmnopqrstuvwxyz1234567890-_';
	$disallowed = array();
	$disallowed['c'] = 'ç';
	$disallowed['n'] = 'ñ';
	$disallowed['y'] = 'ýÿ';
	$disallowed['e'] = 'èéêë';
	$disallowed['a'] = 'àáâãäå';
	$disallowed['o'] = 'ðóòôõöø';
	$disallowed['u'] = 'ùúûü';
	$disallowed['i'] = 'ìíîï';
	
	if(!$allowSpace) $disallowed['-'] = ' ';
	if($allowSpace) $sSafe .= ' ';
	
	if(!$allowDot) $disallowed['-'] = '.';
	if($allowDot) $sSafe .= '.';
	
	if($allowCase){
		$sSafe .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$disallowed['C'] = 'Ç';
		$disallowed['N'] = 'Ñ';
		$disallowed['Y'] = 'ÝŸ';
		$disallowed['E'] = 'ÈÉÊË';
		$disallowed['A'] = 'ÀÁÂÃÄÅ';
		$disallowed['O'] = 'ÐÓÒÔÕÖØ';
		$disallowed['U'] = 'ÙÚÛÜ';
		$disallowed['I'] = 'ÌÍÎÏ';
	}else{
		$myUrl = strtolower($myUrl);
	}
 
	$newString = array();
 
	for($i = 0; $i<strlen($myUrl); $i++){
		$thisChar = $myUrl[$i];
		if(stristr($sSafe, $thisChar)){
			$newString[$i] = $thisChar;
		}else{
			foreach($disallowed as $key => $var){
				if(stristr($var, $thisChar)){
					$newString[$i] = $key;
				}
			}
		}
	}
	return implode('', $newString);
}

$external_link = 0;
$external_id = 0;

$tempString = 'Your name here';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
 
	<head profile="http://gmpg.org/xfn/11"> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<title>Ship - shapeWright.com</title>
		
		<link type="text/css" href="<?=BASE?>css/black-tie/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<link type="text/css" href="<?=BASE?>css/blueprint.css" rel="stylesheet" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		
		<script type="text/javascript" src="<?=BASE?>js/jquery-ui-1.8.16.custom.min.js"></script>
		
		<script type="text/javascript" src="<?=BASE?>js/Three.js"></script>
		<script type="text/javascript" src="<?=BASE?>js/Stats.js"></script>
		
		<script type="text/javascript" src="<?=BASE?>js/RequestAnimationFrame.js"></script>
		
		<script type="text/javascript" src="<?=BASE?>js/ship-small.js"></script>
		
		<script type="text/javascript" src="<?=BASE?>js/swfobject.js"></script>
		<script type="text/javascript" src="<?=BASE?>js/downloadify.min.js"></script>

		<script type="text/javascript">
		<!--
		
var debug = false;
var name, vessel;
var tempString = '<?=$tempString?>';
var pi = Math.PI;
var interVal;
		
var oldNow = false;
var oldAutoY = 0
var newAutoY = 0;

var mouseState = 0;
var mouseFac = 1.0;
		
var targetRotationY = 0;
var targetRotationYOnMouseDown = 0;
var targetRotationX = 0;
var targetRotationXOnMouseDown = 0;

var mouseHeader = false;
var mouseX = 0;
var mouseXOnMouseDown = 0;
var mouseY = 0;
var mouseYOnMouseDown = 0;

var windowHalfX = window.innerWidth / 2;
var windowHalfY = window.innerHeight / 2;
	
var has_gl;
var SCREEN_WIDTH, SCREEN_HEIGHT;
var renderer, camParent, camera, scene, canvas, stats;
var modelParent, Model, Models, Material;
var rendertype;
var facemat, wiremat, blue, grey, black, white, orange;

var external_link = '<?=$external_link?>';
var external_id = <?=$external_id?>;
var external_name;

var sceneLimit = 4000.0;
var sceneHalf = sceneLimit * 0.5;

var building = true;
var checkTargets = false;
var partTotal = 0;
var partCount = 0;
var partLimit = 6;
var partVar = 4;
var infancy = 1500;
var partOffset = 192;
var activeParts = new Array();
var Connectors = new Array();
var newConnectors = new Array();

var stars;
var starSpeed = 0.72;

var rendertype = false;

var blue = 0x03106D;
var grey = 0x383838;
var white = 0xEEEEEE;
var black = 0x000000;
var orange = 0xFF6600;

var partRand, frameRand;

$(document).ready(function(){

	name = makeSafeUrl($('#name').val(),true);
	$('#name').val(name);
	
	if(name.length && name != tempString){
	
		if(debug){ console.log('seeding with ',name); }
	
		partRand = new Rc4Random(''+name);
		frameRand = new Rc4Random(''+name);
		//Math.seedrandom(name,true);
		//alert('seeding with '+name+partRand.getRandomNumber());
		
		vessel = 'the '+name;
	}else{
	
		name = makeSafeUrl($('#randString').val(),true);
		partRand = new Rc4Random(''+name);
		frameRand = new Rc4Random(''+name);
		vessel = 'a random vessel<br />Enter your name to create one of your own!';
		
	}

	var currentTime = new Date()
	oldNow = currentTime.getTime();
	
	$('#message').hide();

	has_gl = 0;
	
	SCREEN_WIDTH = window.innerWidth; //$('#canvas').width();
	SCREEN_HEIGHT = window.innerHeight; // - $('#header').height(); //$('#canvas').height();
	
	try {
		message('Creating webgl renderer',false, true);
		
		// create a WebGL renderer
		renderer = new THREE.WebGLRenderer({antialias: true});
		renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
		
		canvas = document.getElementById('canvas');
		if(debug){
			stats = new Stats();
			stats.domElement.style.position = 'absolute';
			stats.domElement.style.top = '28px';
			stats.domElement.style.left = '0px';
			canvas.appendChild( stats.domElement );
		}
			
		// attach the render-supplied DOM element
		canvas.appendChild(renderer.domElement);
		rendertype = 'webgl';
		
	}catch (e) {

		message("Your browser doesn\'t support WebGL<br />Find out how to get WebGL <a href=\"http://get.webgl.org/\"><u>here</u></a>",true, false);
		
	}
	
	
	if(rendertype){
	
		message('Loading scene',false, true);

		camera = new THREE.TrackballCamera({

			fov: 35, 
			aspect: SCREEN_WIDTH / SCREEN_HEIGHT,
			near: 1,
			far: sceneHalf,

			rotateSpeed: 6.0,
			zoomSpeed: 1.2,
			panSpeed: 0.8,

			noZoom: false,
			noPan: true,

			//staticMoving: false,
			//dynamicDampingFactor: 0.3,

			keys: [ 65, 83, 68 ],
			
			domElement:document.getElementById('canvas')

		});
		
		camera.position.y = 250;
		camera.position.z = -1000;
		
		// Set up the camera (angle, width, height, near, far)
		//camera = new THREE.Camera(75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000);
		//camera.position.z = 450;
		
		camParent = new THREE.Object3D();
		modelParent = new THREE.Object3D();
		modelParent.rotation.x = -(pi * 0.5);
		modelParent.position.y = -50;
		
		// Create the scene
		scene = new THREE.Scene();
		scene.fog = new THREE.Fog(black, (sceneHalf-300), sceneHalf);
		
		scene.addObject(modelParent);
		
		partMat = new THREE.ParticleBasicMaterial( { 30: 30 } );
		partMat.color.setHSV( white, white, white );

		geometry = new THREE.Geometry();
		
		for (var ix = 0; ix < 64; ix++ ) {
			vector = new THREE.Vector3( (frameRand.getRandomNumber()*sceneLimit)-sceneHalf,(frameRand.getRandomNumber()*sceneLimit)-sceneHalf,(frameRand.getRandomNumber()*sceneLimit)-sceneHalf);
			geometry.vertices.push( new THREE.Vertex( vector ) );
		}

		stars = new THREE.ParticleSystem( geometry, partMat);
		modelParent.addChild( stars );
		
		camParent.addChild(camera);
		
		scene.addObject(camParent);
		
		facemat = new THREE.MeshBasicMaterial( { color: white, opacity: 1.0, shading: THREE.FlatShading } );
		wiremat = new THREE.MeshBasicMaterial( { color: grey, opacity: 1.0, wireframe: true, wireframeLinewidth: 1.0 } );
		
		Material = [facemat,wiremat]; 
		
		message('Constructing '+vessel,false, true);
		
		addModel();
		
		initUi();
		initColorpicker();
		initDownload();
		
		updateSettings();
		
		$('#container').show();
		
		canvas = document.getElementById('canvas');
		
		window.addEventListener('resize', onWindowResize, false);
		canvas.addEventListener( 'mousedown', onDocumentMouseDown, false );
		canvas.addEventListener( 'touchstart', onDocumentTouchStart, false );
		canvas.addEventListener( 'touchmove', onDocumentTouchMove, false );

		animate();
		
	}
	
});


function updateSettings(){

	name = makeSafeUrl($('#name').val(),true);
	
	if(name.length && name != tempString){
	
		
	}else{
	
		name = makeSafeUrl($('#randString').val(),true);
		
	}

	$('#buySettings').val(name+'-'+$('#colorpicker li.active a').attr('title'));

}



// Add the 3d model
function addModel(segments){

	partLimit += Math.round(partRand.getRandomNumber() *partVar)

	Models = new Array();

	basePart = new THREE.Mesh( new Ship('hull'),  Material);
	basePart.doubleSided = false;
	basePart.useQuaternion = true;
	var currentTime = new Date()
	basePart.birthTime = currentTime.getTime();
	
	/*
	max = 0;
	bigvals = '';
	
	for (var i = 0; i < basePart.geometry.parts.length; i ++ ){
		
		console.log('checking object',i,'of',basePart.geometry.parts.length);
		
		p = basePart.geometry.parts[i];

		for (var j = 0; j < p.vertices.length; j ++ ){
		
			for (var k = 0; k < 3; k ++ ){
			
				checkMe = p.vertices[j][k];
				checked = 0;
				checkNat = Math.abs(checkMe)
				
				if(checkNat != 0.0 && checkNat != 17.92 && checkNat != 23.665){
			
					for (var l = 0; l < basePart.geometry.parts.length; l ++ ){
					
						q = basePart.geometry.parts[l];

						for (var m = 0; m < q.vertices.length; m ++ ){
						
							for (var n = 0; n < 3; n ++ ){
							
								checkAgainst = Math.abs(q.vertices[m][n]);
								
								if(checkNat == checkAgainst){
									checked += 1;
								}
							
							}
						}
					}
					
					
					if(checked > 50){
						//max = checked;
						bigvals = bigvals + ' ,'+checkNat+' ';
					}
					
				}
			
			}
		}
	}
	
	//console.log('max',max);
	console.log('bigvals',bigvals);
	*/
	
	modelParent.addChild(basePart);
	
	Models.push(basePart);
	
	// Make a neat list of hulls and parts if we're debugging
	if(debug){
	
		hulls = 'hulls';
		for (var i = 0; i < basePart.geometry.hulls.length; i ++ ){
			hulls = hulls+', '+i+' '+basePart.geometry.hulls[i].name;
		}	
		console.log(hulls);
		
		parts = 'parts';
		for (var i = 0; i < basePart.geometry.parts.length; i ++ ){
			parts = parts+', '+i+' '+basePart.geometry.parts[i].name;
		}
		console.log(parts);
		
		console.log('using',basePart.geometry.part.name,'as a base');
		
	}
	
	addConnections(basePart);
	for (var g = 0; g < newConnectors.length; g ++ ){
		Connectors.push(newConnectors[g]);
	}
	newConnectors = new Array();
	
	c//onsole.log(basePart);
	delete basePart.geometry.parts, basePart.geometry.hulls;
	
}



function addPart(depth, cPick,pPick){

	// No deeper than two parts!
	if(depth > 1){
		return;
	}
	
	depth += 1;
	partCount += 1;

	// Pick a connection
	if(cPick === undefined){
		cPick = Math.round(partRand.getRandomNumber() * (Connectors.length - 1));
		if (debug){ console.log('picking connection ',cPick); }
	}
	//cPick = 0;
	connection = Connectors[cPick];
	
	// Make a part
	if(pPick === undefined){
		part = new THREE.Mesh( new Ship('part'),  Material);
		pPick = part.geometry.part.id;
	}else{
		part = new THREE.Mesh( new Ship('part', pPick),  Material);
	}
	
	var currentTime = new Date()
	part.birthTime = currentTime.getTime();
	
	part.doubleSided = false;
	part.useQuaternion = true;
	modelParent.addChild(part);
	Models.push(part);
	activeParts.push(part);
	
	// Add the female connectors to the Connectors list!			
	if(debug){ console.log(partCount,'connecting',part.geometry.part.name,'to', connection.name); }
	
	d = new THREE.Quaternion();
	d.copy(connection.quaternion);

	part.quaternion.copy(d);
	part.position.copy(connection.position);
	
	part.update();
	
	part.target = new THREE.Vector3();
	part.target.copy(part.position);
	
	part.offset = new THREE.Vector3(0.0,0.0,1.0);
	part.offset = d.multiplyVector3(part.offset); // part.matrix.multiplyVector3(part.offset);
	part.offset.setLength(partOffset);
	part.position.addSelf(part.offset);
	
	// Remove the connection that just got attached
	Connectors.splice(cPick,1); 
	
	// Add the new connections from this part
	addConnections(part);
	
	delete part;
	
	// See if there's another connector mirroring this one
	cLen = Connectors.length
	if(cLen){
		cV = new THREE.Vector3();
		cV.copy(connection.position);
		cC = new THREE.Vector3();
		//cy = rounder(connection.position.y)
		//cz = rounder(connection.position.z)
		
		for (var g = 0; g < cLen; g++ ){
			c = Connectors[g];
			cC.copy(c.position);
			cC.x = -cC.x
			dist = cC.distanceTo(cV);
			if(dist < 0.5){ //rounder(c.position.y) == cy && rounder(c.position.z) == cz){
				addPart(depth,g, pPick);
				break;
			}
		}
	}
	delete dist,cV,cC,c,g,depth,pPick,cPick;

}


// Make some new Connectors available!
function addConnections(part){

	con = part.geometry.part.connectors;
	cLen = con.length;
	
	if(cLen){
		mat = part.matrix;
			
		for (var g = 0; g < cLen; g++){
			
			c = con[g];
			
			n = new THREE.Object3D();
			n.name = c.name;
			n.position = new THREE.Vector3(c.position[0],c.position[1],c.position[2]);
			n.quaternion = new THREE.Quaternion(c.quaternion[0],c.quaternion[1],c.quaternion[2],c.quaternion[3]);
			//n.quaternion.copy(c.quaternion);
			n.useQuaternion = true;
			n.update();
			
			m = new THREE.Matrix4();
			m.multiply(mat,n.matrix);
			
			n.position = m.getPosition();
			n.quaternion.setFromRotationMatrix(m);

			newConnectors.push(n);
		}
	}
	
	delete con,cLen,mat,m,c,n;
	
}

// Animate function
function animate() {

	requestAnimationFrame( animate );
	render();
	if(debug){
		stats.update();
	}

}

// Each render of a frame!
// We update the models in here
function render() {

	var currentTime = new Date()
	now = currentTime.getTime();
	timeSpent = now - oldNow;
		
	// Enable these lines in case we want to modify the geometry in stead of swap it
	//Model.geometry.__dirtyVertices = true;
	//Model.geometry.__dirtyNormals = true;
	
	if(building){
	
		// If we're stepping, just move the parts!
		if(activeParts.length){
			
			for (var g = 0; g < activeParts.length; g ++ ){
			
				part = activeParts[g];
				lifeTime = now - part.birthTime;

				if(lifeTime < infancy){
				
					factor = 1.0 - (lifeTime / infancy);
					factor *= factor * factor;
					dist = (factor * partOffset);
					
					part.offset.setLength(dist);
					
					part.position.add(part.target, part.offset);
					
					delete  factor, dist;
				}else{
					activeParts = new Array();
					break;
				}
				delete part, lifeTime;
			
			}
			delete g;
			
		// If we haven't reached our limit yet, let's add a fresh part
		}else if(partCount < partLimit){
		
			for (var m = 0; m < activeParts.length; m++ ){
				Model = activeParts[m];
				if(Model.target !== undefined){
					Model.position.copy(Model.target);
				}
			}
	
			activeParts = new Array();
			newConnectors = new Array();
			if(debug){ console.log('adding part',partCount); }
			addPart(0);
			for (var g = 0; g < newConnectors.length; g ++ ){
				Connectors.push(newConnectors[g]);
			}
			delete g;
			
		// If we're at our limit... let's clean up the active parts
		}else{
			delete activeParts;
			
			if(debug){ console.log('checking targets'); }
			for (var m = 0; m < Models.length; m++ ){
				Model = Models[m];
				if(Model.target !== undefined){
					Model.position.copy(Model.target);
				}
			}
			
			building = false;
			/*
			if((!name.length) || name == tempString){
				message('<div style="margin-top: 30px;" \>Fill in your name at the top to generate a unique ship!</div>', false, false);
				setTimeout(function(){ $("#message").fadeOut() }, 5000);
			}else{
				$('#message').hide();
			}*/
			$('#message').hide();
			$('#upload').show();
			$('#buyForm').show();
			$('#download').show();
		}
		
	}
	
	
	// Lets update the stars positions
	sg = stars.geometry;
	sg.__dirtyVertices = true;
	sv = sg.vertices
	
	for (var g = 0; g < sv.length; g ++ ){
		sp = sv[g].position;
		
		// If the star moved beyond the boundary give it a new random starting position
		if(sp.y > sceneHalf){
			sp.x = (frameRand.getRandomNumber()*sceneLimit)-sceneHalf;
			sp.y = (-(frameRand.getRandomNumber()*sceneHalf) * 0.1) - sceneHalf;
			sp.z = (frameRand.getRandomNumber()*sceneLimit)-sceneHalf;
			
		// Move the star along it's path
		}else{
			sp.y += (timeSpent * starSpeed);
		}
	}
	delete sg, sv, sp, g
	
	
	//now /= 5000;
	newAutoY = pi * now;
	newAutoY *= 0.00015;
	
	difAutoY = oldAutoY - newAutoY;
	
	if(mouseState == 1 && mouseFac > 0.0){
		mouseFac -= 0.05;
	}else if(mouseState == 0 && mouseFac < 1.0){
		mouseFac += 0.005;
	}
	if(mouseFac < 0.0){
		mouseFac = 0.0;
	}else if(mouseFac > 1.0){
		mouseFac = 1.0;
	}
	
	difAutoY *= mouseFac;
	delete mouseFac;
	
	modelParent.rotation.z += difAutoY;
	
	oldNow = now;
	oldAutoY = newAutoY;
	delete now, timeSpent, difAutoY, newAutoY;
	
	renderer.render(scene, camera);

}



// Init the interface (with sliders and such);
function initUi(){

	$('#support').click(function(){
		$('#upload, #buyForm, #download, #support').hide();
		$('#donations, #mask').show();
	});
	
	$('#clickhere, #mask').click(function(){
		$('#donations, #mask').hide();
		if($('#header:visible').length){
			$('#upload, #buyForm, #download, #support').show();
		}else{
			$('#support').show();
		}
	});

	$('#shipForm .submit').click(function(){
		form = $(this).parents('form').eq(0);
		$('#name').val(makeSafeUrl($('#name').val(),1));
		if($('#name').val() == '') $('#name').val(tempString);
		if($('#name').val() == tempString){
			 window.location = form.attr('action');
		}else{
			form.submit();
		}
	});
	
	$('#name').focus(function(){
		if($(this).val() == tempString){
			$(this).val('');
		}
	});
	$('#name').blur(function(){
		if(makeSafeUrl($(this).val()) == ''){
			$(this).val(tempString);
		}
	});
	$('#name').focusout(function(){
		if(makeSafeUrl($(this).val()) == ''){
			$(this).val(tempString);
		}
	});

}


// Check the model!
function runModelCheck(){

	message('Model loaded!<br />Please wait whilst we run checks<br />(will take a few minutes)<br /><br />Keep this window open!', false, true);
	cronshapeways();
	interVal = setInterval("cronshapeways()",60000);

}


function getDownload(){
	getModelData();
	$('#upload, #buyForm, #support').hide();
	message('Loading model data...<br />please wait', false, true);
	return $('#wrl').val();
}

// Download the model on click of the submit link
function initDownload(){


	// Make downloadify!
	//
	//$("#downloadigy").downloadify({
	Downloadify.create('downloadify',{
		filename: 'ship.wrl',
		data: function(){
			return getDownload();
		},
		onComplete: function(){ 
			$('#message').hide();
			<?php
			if(!stristr(ROOT, 'htdocs')){
				?>$('#donations, #mask').show();<?php
				}
			?>
		},
		onCancel: function(){
			$('#message').hide();
			$('#upload, #buyForm, #support').show();		
		},
		onError: function(){
			alert('Sorry, something went wrong!');
			$('#message').hide();
			$('#upload, #buyForm, #support').show();		
		},
		transparent: false,
		swf: '<?=BASE?>js/downloadify.swf',
		downloadImage: '<?=BASE?>img/download.png?rev=2',
		width: 114,
		height: 26,
		transparent: true,
		append: false
	});
	
	
	// Upload form
	$('#upload .submit').click(function(){
		$('#upload').eq(0).submit();
	});

	/*
	$('#uploadDisabled .submit').click(function(){
		
		$('#header, #upload, #download, #fb-buttons').hide();
		
		message('Loading model data...<br />please wait', false, true);
		
		if(parseInt(external_id) == external_id && external_id > 0){
			runModelCheck();
			return;
		}else{
		
			
			set = makeSafeUrl($('#name').val(),true);
		
			if(set == tempString){
				set = '';
			}
			
			$('form .set').val(set);
			
			// First we submit for a generic check to see if this model has been done before... and if so... we just check that one first!
			$.post(
				"check.php", {
					settings: $('#upload .set').val()
				},
			   function(data) {
					if(parseInt(data) == data && data > 0){
						external_id = data;
						runModelCheck();
						return;
					}else{

						// First get the data
						getModelData();
						
						// Now just submit the form with the points and faces.. easy
						$.post(
							"upload.php", {
								name: 'Ship',
								settings: $('#upload .set').val(),
								points: $('#upload .points').val(),
								faces: $('#upload .faces').val()
							},
						   function(data) {
								if(parseInt(data) == data){
									external_id = data;
									runModelCheck();
									return;
								}else{
									message(data, true, false);
								}
						   });
					
					
					}
			   });
			   
		}
	});

	/*
	$('#download .submit').click(function(){
		
		message('Loading model data... please wait', false, true);
		
		// First get the data
		getModelData();
		
		// http://www.shapeways.com/model/319134/ring_1.html
		// Now just submit the form.. easy
		$(this).parent().submit();
		
		$('#upload, #download #support').hide();
		$('#donations, #mask, #downloading').show();
		
		$('#upload, #download').show();
		$('#message').hide();

	});
	*/
}

// Check the cron job man!
function cronshapeways(){
	
	$.post(
		"cronshapeways.php", {
			id: external_id
		},
	   function(data) {
			if(data == external_id){
				window.location = 'http://www.shapeways.com/cart/addMultiple?model_id[]='+external_id+'&count[]=1&material_id[]=62';
			}else{
			
				if(data == 'error'){
					message('Woops an error occured.<br />This is experimental stuff... it happens.', true, false);
					clearInterval(interVal);
				}else if(data.length){
					message(data, true, false);
					clearInterval(interVal);
				}
				
				if(debug){ console.log(data); }
			}
	   });

}



// Get the points and faces for the model!
function getModelData(){

	modelParent.rotation.x = -(pi * 0.5);
	modelParent.rotation.z = 0.0;
	pointList = '';
	faceList = '';
	offset	 = 0;
	
	if(debug){ console.log('getting',Models.length,'models'); }
	
	for (var m = 0; m < Models.length; m++ ){
		
		// Lets get all the coordinates for the points in a neat string
		Model = Models[m];
		
		if(debug){ console.log(m, Model.geometry.part.name); }
		
		verts = Model.geometry.vertices;
		mat = Model.matrixWorld
		vLen = verts.length
		
		if(debug){ console.log('adding',vLen,'verts'); }
		
		for (var i = 0; i < vLen; i ++ ){
		
			p = mat.multiplyVector3(verts[i].position);
			//p = verts[i].position;
			
			x = roundIsh(p.x);
			y = roundIsh(p.y);
			z = roundIsh(p.z);
			
			pointList += '            '+x+' '+y+' '+z+",\r\n";
		}
		
		
		// Get all the faces (indexes of points) in a neat string
		
		faces = Model.geometry.faces;
		fLen = faces.length;
		
		if(debug){ console.log('adding',fLen,'faces'); }
		
		for (var i = 0; i < fLen; i ++ ){
		
			v = faces[i];
			
			a = v.a+offset;
			b = v.b+offset;
			c = v.c+offset;
			d = v.d+offset;
			
			// Make two tris (no quads in wrl);
			faceList += '          '+a+', '+b+', '+c+", -1,\r\n";
			// Only make the second tri if it's not a quad
			if(!isNaN(d)){
				faceList += '          '+a+', '+c+', '+d+", -1,\r\n";
			}
			
		}
		
		offset += vLen;
		
	}
	
	set = makeSafeUrl($('#name').val(),true);
	
	if(set == tempString){
		set = '';
	}
	
	
	wrl = $('#wrl').val();
	wrl = wrl.replace(/namedata/gi, '<?=PART?>');
	wrl = wrl.replace(/pointdata/gi, pointList);
	wrl = wrl.replace(/facedata/gi, faceList);
	$('#wrl').val(wrl);
	
	$('form .set').val(set);
	$('form .points').val(pointList);
	$('form .faces').val(faceList);

}


// Pick a color scheme for the model
function initColorpicker(){

	$('#colorpicker').show();

	$('#colorpicker a').click(function(){
	
		if(!$(this).parent().is('.active')){
		
			style = $(this).attr('title');

			if(style == 'white'){
				facemat.color = new THREE.Color( white );
				wiremat.color =  new THREE.Color( grey );
			}else if(style == 'blue'){
				facemat.color =  new THREE.Color( blue );
				wiremat.color =  new THREE.Color( white );
			}else if(style == 'black'){
				facemat.color =  new THREE.Color( black );
				wiremat.color =  new THREE.Color( orange );
			}

			$('#colorpicker li.active').removeClass('active');
			$(this).parent().addClass('active');
			
			updateSettings();
		}
	
	});

}

function onWindowResize( event ) {
	SCREEN_WIDTH = window.innerWidth;
	SCREEN_HEIGHT = window.innerHeight; // - $('#header').height();
	camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
	camera.updateProjectionMatrix();
	renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
}
function onDocumentMouseDown( event ) {
	event.preventDefault();
	canvas.addEventListener( 'mouseup', onDocumentMouseUp, false );
	canvas.addEventListener( 'mouseout', onDocumentMouseOut, false );
	mouseState = 1;
}
function onDocumentMouseUp( event ) {
	canvas.removeEventListener( 'mouseup', onDocumentMouseUp, false );
	canvas.removeEventListener( 'mouseout', onDocumentMouseOut, false );
	mouseState = 0;
}
function onDocumentMouseOut( event ) {
	canvas.removeEventListener( 'mouseup', onDocumentMouseUp, false );
	canvas.removeEventListener( 'mouseout', onDocumentMouseOut, false );
	mouseState = 0;
}
function onDocumentTouchStart( event ) {
	if ( event.touches.length == 1 ) {
		event.preventDefault();
	}
}
function onDocumentTouchMove( event ) {
	if ( event.touches.length == 1 ) {
		event.preventDefault();
	}
}



// Just make sure we've got slightly neat numbers for exports
function roundIsh(nr){
	return Math.round(nr * 10000000000) / 10000000000;
}
function rounder(nr){
	return Math.round(nr * 100) / 100;
}

// Convert degrees to radians
function radians(nr){
	return pi * (nr/180);
}

// Convert radians to degrees
function degrees(nr){
	return nr * (180/pi);
}

// Display a message!
function message(m, b, l){
	$('#message').html(m);
	$('#message').show();
	if(b){
		$('#message').addClass('error');
	}else{
		$('#message').removeClass('error');
	}
	if(l){
		$('#message').addClass('loading');
	}else{
		$('#message').removeClass('loading');
	}
}

// Function for making sure text only uses url safe symbols
function makeSafeUrl(thisText, allowSpace){
	var w = "!@#$%^&*()+=[]\\\';,./{}|\":<>?";
	var s = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
	var x = new Array('àáâãäå', 'ç', 'èéêë', 'ìíîï', 'ñ', 'ðóòôõöø', 'ùúûü', 'ýÿ');
	var r = new Array('a', 'c', 'e', 'i', 'n', 'o', 'u', 'y');

	if(allowSpace){
		s = s + ' ';
	}else{
		x.push(' ');
		r.push('_');
	}

	//thisText = thisText.toLowerCase();
	var newText = new Array();

	for (i = 0; i < thisText.length; i++){
		thisChar = thisText.charAt(i);
		if(w.indexOf(thisChar) == -1){
			if(s.match(''+thisChar+'')){
				newText[i] = thisChar;
			}else{
				for (j = 0; j < x.length; j++){
					if(x[j].match(thisChar)){
						newText[i] = r[j];
					}
				}
			}
		}
	}

	return newText.join('');
}

function Rc4Random(seed)
{
	var keySchedule = [];
	var keySchedule_i = 0;
	var keySchedule_j = 0;
	
	function init(seed) {
		for (var i = 0; i < 256; i++)
			keySchedule[i] = i;
		
		var j = 0;
		for (var i = 0; i < 256; i++)
		{
			j = (j + keySchedule[i] + seed.charCodeAt(i % seed.length)) % 256;
			
			var t = keySchedule[i];
			keySchedule[i] = keySchedule[j];
			keySchedule[j] = t;
		}
	}
	init(seed);
	
	function getRandomByte() {
		keySchedule_i = (keySchedule_i + 1) % 256;
		keySchedule_j = (keySchedule_j + keySchedule[keySchedule_i]) % 256;
		
		var t = keySchedule[keySchedule_i];
		keySchedule[keySchedule_i] = keySchedule[keySchedule_j];
		keySchedule[keySchedule_j] = t;
		
		return keySchedule[(keySchedule[keySchedule_i] + keySchedule[keySchedule_j]) % 256];
	}
	
	this.getRandomNumber = function() {
		var number = 0;
		var multiplier = 1;
		for (var i = 0; i < 8; i++) {
			number += getRandomByte() * multiplier;
			multiplier *= 256;
		}
		return number / 18446744073709551616;
	}
}



		-->
		</script>
	</head>
	<body>
	
		<div id="canvas"></div>
		
		<div id="container">
	
			<div id="header">
			
				<div id="colorpicker">
					<ul>
						<li class="active"><a href="javascript: void(0);" title="white" class="white"></a></li>
						<li><a href="javascript: void(0);" title="blue" class="blue"></a></li>
						<li><a href="javascript: void(0);" title="black" class="black"></a></li>
					</ul>
				</div>
				
				<h1><a href="http://www.shapewright.com/">shape<strong>Wright</strong>.com</a></h1>
				
				<form id="shipForm" method="get" action="<?=BASE?>">
					<div id="entry">
						<input type="text" name="name" id="name" value="<?=((isset($_GET['name']) && !empty($_GET['name']) && strlen($_GET['name'])) ? $_GET['name'] : $tempString)?>" />
						<a href="javascript: void(0);" class="submit"></a>
					</div>
				</form>
				
				<form id="randForm" method="post" action="<?=BASE?>">
					<input type="text" name="randString" id="randString" value="<?=makeRandomString(16)?>" />
				</form>
				
			</div>
			
			<div id="dynamics">

				<div id="alerts">
					<div id="loader">&nbsp;</div>
					<span id="message" class="error">Javascript is still loading or not running as expected.<br />This page uses WebGL (works in google chrome and most firefox browsers)<br />Find out how to get WebGL <a href="http://get.webgl.org/">here</a></span>
				</div>
				
				<?php /*
				<form id="uploadDisabled" method="post" action="upload.php">
					<input type="hidden" name="name" value="Ship" />
					<input type="hidden" name="settings" class="set" value="" />
					<textarea name="points" class="points" cols="10" rows="3"></textarea>
					<textarea name="faces" class="faces" cols="10" rows="3"></textarea>
					<a href="<?=(($external_link) ? $external_link : 'javascript: void(0);" class="submit')?>">Buy <span>3D</span> print</a>
				</form> */ 
				/*
				<form action="https://www.paypal.com/cgi-bin/webscr" id="upload" method="post">
					<div id="buyDesc">
						<h3>Get this unique ship printed for <span>&euro; 49.-</span></h3>
						<ul>
							<li>Ships are printed in plastic.<br />Approximately 5 to 10cm in length.</li>
							<li>The ship is printed in the colour you picked<br />(pick at the right top corner of this page, <em>the lines are not printed)</em></li>
							<li>The price includes worldwide shipping.</li>
							<li>Please allow up to 4 weeks for delivery.</li>
						</ul>
					</div>
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="HKVARWFU3NNNL">
					<input type="hidden" name="on0" value="Type">
					<input type="hidden" name="os0" value="Ship" />
					<input type="hidden" name="on1" value="Settings">
					<input type="hidden" name="os1" id="buySettings" maxlength="200">
					<input type="hidden" name="currency_code" value="EUR">
					<a href="javascript: void(0);" class="submit">Buy <span>3D</span> print</a>
					<?php /*<input type="image" src="http://www.shapewright.com/buy.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1"> */ 
				/*</form> */
				?>

				
			</div>
			
		</div>
		<?php /*
		<div id="fb-buttons">
			<!-- AddThis Button BEGIN -->
			<script type="text/javascript">
			var addthis_config =
			{
			   services_exclude: 'print',
			}
			<?php if(isset($_GET['name']) && !empty($_GET['name']) && strlen($_GET['name']) && $_GET['name'] != $tempString){ ?>
				var addthis_share = {
					url: 'http://ship.shapewright.com',
					url_transforms : {
						add: {
							name: '<?=$_GET['name']?>'
						}
					}
				}			
		<?php }else{ ?>
				var addthis_share = {
					url: 'http://ship.shapewright.com'
				}		
		<?php } ?>
			</script>
			<div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
			</div>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e7ceaa8698c7481"></script>
			<!-- AddThis Button END -->
		</div>
		*/
		?>
		
		<form id="download" method="post" action="download.php">
			<input type="hidden" name="name" value="Ship" />
			<input type="hidden" name="settings" class="set" value="" />
			<textarea name="points" class="points" cols="60" rows="3"></textarea>
			<textarea name="faces" class="faces" cols="60" rows="3"></textarea>
			<textarea name="filecontent<?=time()?>" id="wrl">#VRML V2.0 utf8

Transform {
  children [
    DEF ME_namedata Shape {
      appearance Appearance {
        material Material {
          ambientIntensity 0.1667
          diffuseColor 0.8 0.8 0.8
          specularColor 0.4012 0.4012 0.4012
          emissiveColor 0 0 0
          shininess 0.0977
          transparency 0
        }
        texture NULL
        textureTransform NULL
      }
      geometry IndexedFaceSet {
        color NULL
        coord Coordinate {
          point [
pointdata
          ]
        }
        colorIndex [ ]
        coordIndex [
facedata
        ]
        normal NULL
        creaseAngle 0
        solid TRUE
      }
    }
  ]
}</textarea>
			<!--<a href="javascript: void(0);" class="submit">Download model as VRML file</a>-->
			<p id="downloadify" style="height: 26px; width: 114px;">
				You must have Flash 10 installed to download this file.
			</p>
		</form>
		
		<div id="footer">The ShapeWright is an experiment by <a href="http://www.macouno.com/">macouno</a> &copy; <?=date('Y')?>. <a href="javascript: void(0);" id="support">Help support the development and maintenance of this page!</a></div>
		
		<div id="mask"></div>
		<div id="donations">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><a href="javascript: void(0);" id="clickhere">click here to close</a><p>Help keep this service free,<br />and support further development!</p>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GQGSGJGLPFN46">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
</form>
		</div>
		<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-47994657-2', 'shapewright.com');
	  ga('send', 'pageview');

	</script>

	</body>
</html>