OUTPUT_ZIP := cf7-ciuu-list.zip

OUTPUT_DIR := "/Users/jlcaicedo/Desktop/Jose Luis Caicedo/Personal Projects.nosync/CompilerOut/wordpress"

zip:
	zip -r $(OUTPUT_DIR)/$(OUTPUT_ZIP) ./* --exclude 'Makefile' --exclude '.gitignore' --exclude '.github' --exclude '.wordpress-org' --exclude '.distignore' --exclude '.gitattributes' --exclude '.git'

all: zip

.PHONY: all zip
