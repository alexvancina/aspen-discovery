package org.aspen_discovery.reindexer;

class ARTitle {
	private String title;
	private String author;
	private String bookLevel;
	private String arPoints;
	private String interestLevel;

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	@SuppressWarnings("unused")
	String getAuthor() {
		return author;
	}

	void setAuthor(String author) {
		this.author = author;
	}

	String getBookLevel() {
		return bookLevel;
	}

	void setBookLevel(String bookLevel) {
		this.bookLevel = bookLevel;
	}

	String getArPoints() {
		return arPoints;
	}

	void setArPoints(String arPoints) {
		this.arPoints = arPoints;
	}

	String getInterestLevel() {
		return interestLevel;
	}

	void setInterestLevel(String interestLevel) {
		this.interestLevel = interestLevel;
	}
}
