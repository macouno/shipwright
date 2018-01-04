var Ring = function () {

	var scope = this;
	
	scope.scale = 10;
	
	scope.settings = new Array();
	
	// name, value, minimum, maximum, step, factor, value description
	scope.settings[0] = new Array('size',150,100,300,1,0.1,'mm');
	scope.settings[1] = new Array('thickness',24,12,120,1, 0.1,'mm');
	scope.settings[2] = new Array('width',72,0,126,1,0.1,'mm');
	scope.settings[3] = new Array('shape',100,0,100,1,1,'%');
	scope.settings[4] = new Array('detail',36,6,144,2,1,'&nbsp;segments');
	
	for (var i = 0; i < scope.settings.length; i ++ ){
	
		id = '#'+scope.settings[i][0];
		
		val = $(id).slider('value')
		
		if(typeof(val) == 'number'){
			scope.settings[i][1] = val;
		}
	
	}

	THREE.Geometry.call( this );
	
	diameter = scope.settings[0][1] * scope.settings[0][5];
	thickness = scope.settings[1][1] * scope.settings[1][5];
	width = (scope.settings[2][1] * scope.settings[2][5]) * 0.5;
	shape = (scope.settings[3][1] * scope.settings[3][5]) / 100;
	steps = scope.settings[4][1] * scope.settings[4][5];
	
	rings = Math.round(steps * 0.2);
	if(rings < 6){
		rings = 6;
	}

	radius = diameter * 0.5;
	outer = radius + thickness;
	
	rLeng = thickness * 0.5;
	
	// Make vectors (the midpoint and the vec that rotates around it)
	var sVec = new THREE.Vector3(0.0, (radius+rLeng), 0.0);
	var cVec = new THREE.Vector3(0.0, rLeng, 0.0);
	
	var sAng = radians(360) * (1.0 / steps);
	var cAng = radians(360) * (1.0 / rings);
	
	// Make an axis for the circles
	var cAxis = new THREE.Vector3(1.0, 0.0, 0.0);
	
	var cQuat = new THREE.Quaternion();
	cQuat.setFromAxisAngle(cAxis, cAng);	
	
	var hQuat = new THREE.Quaternion();
	hQuat.setFromAxisAngle(cAxis, (cAng*0.5));	
	
	// Make an axis for the segments
	var sAxis = new THREE.Vector3(0.0, 0.0, 1.0);
	
	var sQuat = new THREE.Quaternion();
	sQuat.setFromAxisAngle(sAxis, sAng);
	
	n = 0;
	half = rings * 0.5;
	
	var circle = []
	
	// First lets just make a circle
	for (var i = 0; i < rings; i++){
		
		if(i > 0){
			cVec = cQuat.multiplyVector3(cVec);
		}else{
			cVec = hQuat.multiplyVector3(cVec);
		}
		
		circle[i] = new THREE.Vector3();
		circle[i].copy(cVec);
		circle[i].addSelf(sVec);
		
	}
	
	// Now make multiple circles!
	for (var i = 0; i < steps; i++){
	
		// Lets make a width factor based on a sine wave
		p = 2.0/steps;
		x = i * p;
		fac = Math.sin(Math.PI * (x+0.5));
		//fac *= 0.5;
		fac += 1.0;
		fac *= 0.5;
		fac *= fac;
		fac = 1.0 - fac;
		
		// Let the shape value set how much of the factor is used
		fac = 1.0 - (fac * shape);
		//console.log(fac);
		
		w = fac * width;
		
		
		// Do all the rings!
		for (var j = 0; j < rings; j++){

			if(i > 0){
				circle[j] = sQuat.multiplyVector3(circle[j]);
			}
			
			// Apply width offset (for shape)
			if(j < half){
				z = circle[j].z + w;
			}else{
				z = circle[j].z - w;
			}
			
			v(circle[j].x,circle[j].y,z);
			
			// Connect this loop to the previous
			if (i > 0 && j > 0){
				a = n;
				b = n-1;
				c = n - (rings+1);
				d = n - rings;
				f4(d,c,b,a);
			}
			
			// Connect the end to the start
			if(j == (rings-1) && i > 0){
				a = n-((rings)+(rings-1));
				b = n-(rings-1);
				c = n;
				d = n - rings;
				
				f4(d,c,b,a);
			}
			
			// Connect the last loop to the first as well
			if(i == (steps-1) && j > 0){
				a = j;
				b = j-1;
				c = n-1;
				d = n;
				f4(d,c,b,a);
			}
			
			
			// Connect the end to the start
			if(j == (rings-1) && i == (steps-1)){
				a = rings-1;
				b= n;
				c = n-(rings-1);
				d = 0;
				f4(d,c,b,a);
			}
			
			
			n+=1;
			
		}
	}

	this.computeCentroids();
	this.computeFaceNormals();

	function v( x, y, z ) {

		scope.vertices.push( new THREE.Vertex( new THREE.Vector3( x, y, z ) ) );

	}

	function f3( a, b, c ) {

		scope.faces.push( new THREE.Face3( a, b, c ) );

	}
	
	function f4( a, b, c,d ) {

		scope.faces.push( new THREE.Face4( a, b, c, d ) );

	}

}

Ring.prototype = new THREE.Geometry();
Ring.prototype.constructor = Ring;
