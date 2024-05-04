YOCABULARY let´s you add english words to your collection, and they would be shown with their definition and an audio with they correct pronunciation. It has an educational purpose, for English students.

***
CRASH UPDATE.
v2

NOT storing anymore audio as a word or parapragph property, BUT on its own TABLE.
Users would have the chance to hear his words AND DEFINITONS in different accents, selectable in main page.
AUDIO TABLE should be filled with audios that users select to be saved, so they should have a reference to every word or paragraph.
A NEW SECTION can be created, in where you HEAR an audio an have the opportunity to write it, and then the system can correct your mistakes. 
AUDIO schema should be like:
CREATE TABLE IF NOT EXISTS audios (
    uuid VARCHAR(255) NOT NULL PRIMARY KEY,
    audio_data MEDIUMBLOB NOT NULL,
    word_uuid VARCHAR(255),
    paragraph_uuid VARCHAR(255),
    FOREIGN KEY (word_uuid) REFERENCES words(uuid),
    FOREIGN KEY (paragraph_uuid) REFERENCES paragraphs(uuid),
    UNIQUE(word_uuid, paragraph_uuid)
);
ALTER TABLE words DROP COLUMN audio_data;
ALTER TABLE words ADD COLUMN audio_uuid VARCHAR(255) NOT NULL UNIQUE;

ALTER TABLE paragraphs DROP COLUMN audio;
ALTER TABLE paragraphs ADD COLUMN audio_uuid VARCHAR(255) NOT NULL UNIQUE;