var Ship = function () {

	var scope = this;
	
	scope.scale = 10;
	
	scope.settings = new Array();
	
	THREE.Geometry.call( this );
	
	scope.connectors = new Object();
	scope.connectors.male = new Array();
	scope.connectors.female = new Array();
	
	c = new THREE.Object3D();
	c.position = new THREE.Vector3(0.0, -13.999996, 15.000004);
	c.rotation = new THREE.Vector3(3.141592, 0.0, -3.141593);
	c.scale = new THREE.Vector3(1.0, 1.0, 1.0);
	scope.connectors['female'].push(c);

	c = new THREE.Object3D();
	c.position = new THREE.Vector3(-1e-06, -13.999996, 15.000004);
	c.rotation = new THREE.Vector3(-3.141593, 0.0, 0.0);
	c.scale = new THREE.Vector3(1.0, 1.0, 1.0);
	scope.connectors['male'].push(c);


	v(11.999999, -24.000001, 53.999996);
	this.computeCentroids();
	this.computeFaceNormals();
	
	function v(x,y,z){
		scope.vertices.push( new THREE.Vertex( new THREE.Vector3(x,y,z)));
	}
	
	function f4(a,b,c,d){
		scope.faces.push( new THREE.Face4(a,b,c,d));
	}

};

Ship.prototype = new THREE.Geometry();
Ship.prototype.constructor = Ship;