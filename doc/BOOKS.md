# Books

This document outlines how books are thought of in the context of this application. At their most
basic, they are collections of text "files", which contain the book's chapters, character
biographies, and location details. Then there are also events, which can be arranged into timelines
to give a better overview about what is happening when.

What follows is a short description about every part of a "book", and how it is planned to be
handled.

## Series

A series is an organizational unit, which multiple books may belong to. Each must have a name and a
short description, which tell us what it is about.

They are to be displayed as a list, which shows their titles, and how many books they contain.

## Book

A book must have a name and a description, it may have a cover image. Each one contains one or more
revisions of their contents.

Books are to be displayed as a tile list, which shows their title and cover image (or a placeholder,
if they have none). This list must be filterable for books which belong to a specific series, or are
unbound.

## Revisions

Revisions contain the real contents of a book, or, to be precise, a specific version of it.
Therefore each must have a number that signifies the order they have been created in. Each holds
their own set of chapters, characters, and locations.

Revisions are to be shown as a drop-down box, with which one can switch between them inside a book.
Switching to a different revisions means that one gets presented with its contents.

## Chapters

Chapters hold the text of a book. They need the following: a title, their content, and a number to
order them by.

The are to be displayed in an Editor.JS instance, which is used to edit them. For that reason, their
content will be the JSON exported from Editor.JS.

If shown as a list, they must be ordered by their number, and their title must be visible.

## Characters

Characters are noted down in free form, and require only the a name and their content.

If shown as a list, they must be listed by their name ordered alphabetically.

A character is also shown in an Editor.JS instance, which is also used to edit them. Because of that
their content will be saved as JSON.

## Locations

Locations are also free form documents, which can be used to note down details about them, and how
they might have changed. They require a name and their content.

If shown as a list, they must be listed by their name ordered alphabetically.

A location is also shown in an Editor.JS instance, which is also used to edit them. Because of that
their content will be saved as JSON.

## Events

Events are meant as chronologically ordered "post its", that can be used to plot a story, or keep
track of what happened in short form. They require: a name, a description, and a signed number that
denotes when an event happened.

There are three ways to display them:

* As a list of their names, ordered either alphabetically, or by their number
* The edit view, where one can set/chance the description and name
* In a timeline

## Timelines

Timelines are used to display a specific set of events in their chronological order. Depending on
what an author wants to plan around, they can add different elements in a timeline. They need a
name, order, and a note. The order controls if events are shown in ascending or descending order
(based on their number). The note is there so that an author can note why they created a particular
timeline.

Timelines are to be displayed as a single page, which moves down as it shows its connected events in
their chronological order. Events are shown in full, with their names and description. Their number
is to formated as a date.
