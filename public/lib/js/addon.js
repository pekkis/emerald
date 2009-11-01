/* Extension to Class-object for class inheritance */
Class.extend = function(source, additions) 
{
	var newclass = Class.create();
	if (source.prototype) {
		Object.extend(newclass.prototype, source.prototype);
	}
	Object.extend(newclass.prototype, additions);
	return newclass;
}
