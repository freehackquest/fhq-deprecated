#include <QJsonObject>
#include <QStringList>
#include <QVector>
#include <QPair>
#include <QMap>
#include <iostream>

#include "generatedocumentation.h"

QString apiToHtml(QJsonObject &api) {
	
	QString result;
	result = "<html>";
	result += "<head>";
	result += "<title>API-Documentation for fhqbackend</title>";
	result += "</head>";
	result += "<body><h1>API-Documentation for fhqbackend</h1>"
	"<table cellspacing=1px cellpadding=5px bgcolor=black><tr>";
	
	QString tableheaders_begin = "";
	tableheaders_begin += "</tr><tr>";
	tableheaders_begin += "<td bgcolor=white>Path</td>";
	tableheaders_begin += "<td bgcolor=white>Method</td>";
	tableheaders_begin += "<td bgcolor=white>Access Level</td>";
	tableheaders_begin += "<td bgcolor=white>Input parameters</td>";
	tableheaders_begin += "<td bgcolor=white>Output</td>";
	tableheaders_begin += "<td bgcolor=white>Description</td>";

	
	
	QVector<QPair<QString,QString> > chapters;
	
	chapters << QPair<QString,QString>("auth", "Authorization");
	chapters << QPair<QString,QString>("users", "Users");
	chapters << QPair<QString,QString>("admin", "Admin");
	chapters << QPair<QString,QString>("games", "Games");
	chapters << QPair<QString,QString>("teams", "Teams");
	chapters << QPair<QString,QString>("services", "Services");
	chapters << QPair<QString,QString>("unknown", "Unknown");
	
	QMap<QString,QString> values;
	for (int i = 0; i < chapters.size(); i++) {
		values[chapters[i].first] = "</tr><tr><td bgcolor=white colspan=6><br><h2>" + chapters[i].second + "</h2></td>" + tableheaders_begin;
	}

	QJsonObject::iterator it = api.begin();
	while(it != api.end()) {
		QJsonObject obj = it.value().toObject();
		QString s = "</tr><tr>"
		"<td valign=top bgcolor=white>" + obj["path"].toString() + "</td>"
		"<td valign=top bgcolor=white>" + obj["method"].toString() + "</td>"
		"<td valign=top bgcolor=white>" + obj["access"].toString() + "</td>"
		"<td valign=top bgcolor=white>";
		// TODO Input
		QJsonObject input = obj["input"].toObject();
		QJsonObject::iterator input_it = input.begin();
		while(input_it != input.end()) {
			s +=  "<b>" + input_it.key() + "</b><br>" + input_it.value().toString() + "<br>";
			++input_it;
		}
		
		
		s +=  "</td>"
		"<td valign=top bgcolor=white>" + obj["output"].toString() + "</td>"
		"<td valign=top bgcolor=white>" + obj["description"].toString() + "</td>";
		
		// sAdminChapter += it.key();
		QString chapter = obj["path"].toString().split("/").at(1);

		if (values.contains(chapter)) {
			values[chapter] += s;
		} else {
			values["unknown"] += s;
		}
		++it;
	}

	for (int i = 0; i < chapters.size(); i++) {
		result += values[chapters[i].first];
	}
	result += "</tr></table></body>";
	result += "</html>";
	return result;
};
