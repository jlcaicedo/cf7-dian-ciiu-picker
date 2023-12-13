OUTPUT_ZIP := wp-security-headers.zip

OUTPUT_DIR := "/Users/jlcaicedo/Desktop/Jose Luis Caicedo/Personal Projects.nosync/CompilerOut"

zip:
	zip -r $(OUTPUT_DIR)/$(OUTPUT_ZIP) ./* --exclude 'Makefile' --exclude '.gitignore'

all: zip

.PHONY: all zip
