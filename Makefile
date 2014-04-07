# build utilities, mostly for less

all: clean css/style.css

css/style.css:
	lessc less/style.less > css/style.css

clean:
	rm css/style.css

