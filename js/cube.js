var Cube = function () {

	var scope = this;
	
	scope.scale = 10;
	
	scope.settings = new Array();
	
	scope.settings[0] = new Array('size',10,5,25,1,1.0,'mm');
	
	for (var i = 0; i < scope.settings.length; i ++ ){
	
		id = '#'+scope.settings[i][0];
		
		val = $(id).slider('value')
		
		if(typeof(val) == 'number'){
			scope.settings[i][1] = val;
		}
	
	}
	
	THREE.Geometry.call( this );
	
	console.log(scope.settings[0][1], scope.settings[0][5]);
	
	s = scope.settings[0][1] * scope.settings[0][5];
	
	
	p = 0.5 * s
	
	console.log('p',p)

	v(p, p, -p);
	v(p, -p, -p);
	v(-p, -p, -p);
	v(-p, p, -p);
	v(p, p, p);
	v(p, -p, p);
	v(-p, -p, p);
	v(-p, p, p);

	f4(0, 1, 2, 3);
	f4(4, 7, 6, 5);
	f4(0, 4, 5, 1);
	f4(1, 5, 6, 2);
	f4(2, 6, 7, 3);
	f4(4, 0, 3, 7);

	
	this.computeCentroids();
	this.computeFaceNormals();
	
	function v( x, y, z ) {

		scope.vertices.push( new THREE.Vertex( new THREE.Vector3( x, y, z ) ) );

	}
	
	function f4( a, b, c,d ) {

		scope.faces.push( new THREE.Face4( a, b, c, d ) );

	}

};

Cube.prototype = new THREE.Geometry();
Cube.prototype.constructor = Cube;
