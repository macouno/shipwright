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


	v(11.999999, -24.000001, 53.999996);	v(11.999999, -24.000001, -23.999999);	v(-12.000002, -24.000001, -23.999996);	v(-11.999996, -24.000001, 54.000006);	v(12.000006, 24.000001, 53.999987);	v(11.999992, 24.000001, -24.000015);	v(-12.000005, 24.000001, -23.999991);	v(-11.999999, 24.000001, 54.000001);	v(12.000006, 54.000001, 23.999989);	v(-11.999999, 54.000001, 24.000001);	v(-12.000005, 54.000001, -23.999991);	v(11.999992, 54.000001, -24.000015);	v(-1.200196, 40.499997, -193.999958);	v(1.199803, 40.499997, -193.999958);	v(1.199803, 37.499998, -193.999958);	v(-1.200196, 37.499998, -193.999958);	f4(3, 2, 1, 0);	f4(1, 5, 4, 0);	f4(2, 6, 5, 1);	f4(3, 7, 6, 2);	f4(7, 3, 0, 4);	f4(8, 9, 7, 4);	f4(9, 10, 6, 7);	f4(11, 8, 4, 5);	f4(11, 10, 9, 8);	f4(13, 12, 10, 11);	f4(14, 13, 11, 5);	f4(12, 15, 6, 10);	f4(15, 14, 5, 6);	f4(12, 13, 14, 15);
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
