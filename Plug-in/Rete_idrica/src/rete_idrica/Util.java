package rete_idrica;


import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.sql.ResultSet;
import java.sql.SQLException;
import javax.swing.JOptionPane;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.osgeo.proj4j.CRSFactory;
import org.osgeo.proj4j.CoordinateReferenceSystem;
import org.osgeo.proj4j.CoordinateTransform;
import org.osgeo.proj4j.CoordinateTransformFactory;
import org.osgeo.proj4j.ProjCoordinate;
import com.vividsolutions.jts.geom.Coordinate;
import com.vividsolutions.jts.geom.Geometry;
import com.vividsolutions.jts.io.ParseException;
import com.vividsolutions.jts.io.WKTReader;
import com.vividsolutions.jump.feature.AttributeType;
import com.vividsolutions.jump.feature.BasicFeature;
import com.vividsolutions.jump.feature.Feature;
import com.vividsolutions.jump.feature.FeatureCollection;
import com.vividsolutions.jump.feature.FeatureDataset;
import com.vividsolutions.jump.feature.FeatureSchema;
import com.vividsolutions.jump.workbench.model.StandardCategoryNames;
import com.vividsolutions.jump.workbench.plugin.PlugInContext;


public class Util {
	
	public static void creaLayer(PlugInContext context, String nome) {
		// Creazione Layer (TRATTE, POZZETTI, PUNTI RU)
		try {
			nome = nome.toUpperCase();
			String query = "";
			FeatureSchema fSchema = new FeatureSchema();
			
			// Imposto la query e il FeatureSchema in base al livello che voglio creare
			if (nome.equals("TRATTA")) {
				query = "SELECT id, diametro, materiale, st_astext(geometry) as wkt FROM tratta WHERE tipologia = TRUE";
				fSchema.addAttribute("ID", AttributeType.INTEGER);
				fSchema.addAttribute("DIAMETRO", AttributeType.INTEGER);
				fSchema.addAttribute("MATERIALE", AttributeType.STRING);
				fSchema.addAttribute("GEOMETRY", AttributeType.GEOMETRY);
			}
			if (nome.equals("RU")) {
				query = "SELECT id, st_astext(geometry) as wkt FROM ru";
				fSchema.addAttribute("ID", AttributeType.INTEGER);
				fSchema.addAttribute("GEOMETRY", AttributeType.GEOMETRY);
			}
			if (nome.equals("POZZETTO")) {
				query = "SELECT id, iniziale, grado, st_astext(geometry) as wkt FROM pozzetto";
				fSchema.addAttribute("ID", AttributeType.INTEGER);
				fSchema.addAttribute("INIZIALE", AttributeType.BOOLEAN);
				fSchema.addAttribute("GRADO", AttributeType.INTEGER);
				fSchema.addAttribute("GEOMETRY", AttributeType.GEOMETRY);
			}
                 
            // Creo la struttura per il layer
            FeatureCollection fCollection = new FeatureDataset(fSchema);
            WKTReader wktr = new WKTReader();
            
            // Analizzo la query di risposta
            ResultSet risultato = DB.eseguiQuery(query, context);
            while(risultato.next())
            {
            	// Setto correttamento gli attributi per ogni feature
                Feature feature = new BasicFeature(fSchema);
                Geometry geometry = wktr.read(risultato.getString("wkt"));
                feature.setAttribute( "ID", risultato.getInt("id") );
                if (nome.equals("TRATTA")) {
                	feature.setAttribute( "DIAMETRO", risultato.getInt("diametro") );
                	feature.setAttribute( "MATERIALE", risultato.getString("materiale") );
    			}
    			if (nome.equals("POZZETTO")) {
    				feature.setAttribute( "INIZIALE", risultato.getBoolean("iniziale") );
    				feature.setAttribute( "GRADO", risultato.getInt("grado") );
    			}
                feature.setGeometry(geometry);
                fCollection.add( feature);
            }
            
            // Creo un nuovo layer
            context.addLayer(StandardCategoryNames.WORKING, nome, fCollection);
            risultato.close();
        } catch (SQLException | ParseException ex) {
        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE LETTURA DATI");
        }
	}
	
	public static void creaEdifici(PlugInContext context, Object[] punti_ru, String nome) {
		try {
			String query = "";
			
            // Creo la struttura per il layer
			FeatureSchema fSchema = new FeatureSchema();
			fSchema.addAttribute("ID", AttributeType.INTEGER);
			fSchema.addAttribute("GEOMETRY", AttributeType.GEOMETRY);
			FeatureCollection fCollection = new FeatureDataset(fSchema);
            WKTReader wktr = new WKTReader();
            ResultSet risultato;
			for (int i = 0; i < punti_ru.length; i++) {
				// Cerco tutti i punti RU interessati dalla modifica
				query = "SELECT id, st_astext(geometry) as wkt FROM ru WHERE id = " + (int) punti_ru[i] + "";
	            risultato = DB.eseguiQuery(query, context);
	            if (risultato.next())
	            {
	            	// Setto correttamento gli attributi per ogni feature
	                Feature feature = new BasicFeature(fSchema);
	                Geometry geometry = wktr.read(risultato.getString("wkt"));
	                feature.setAttribute("ID", risultato.getInt("id") );
	                feature.setGeometry(geometry);
	                fCollection.add(feature);
	            }
			}
            context.addLayer(StandardCategoryNames.WORKING, nome, fCollection);
        } catch (SQLException | ParseException ex) {
        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE LETTURA DATI");
        }
	}
	
	public static String[] ricavaCittadini (Coordinate point)
    {
		// Ricavo l'indirizzo tramite il servizio di geo decoding
		String indirizzo = ricavaIndirizzo(point);
		String ind = indirizzo.replace(" ", "");
		InputStream inputStream = null;
        BufferedReader bufferReader = null;
        String line = "";
 		String file = "";
        try {
			// Definisco l'indirizzo a cui effettuare la richiesta dei cittadini
        	URL url = new URL("http://www.sawaz.it/Univ/GIS/Progetti/anagrafe.php?request=residenti&indirizzo=" + ind);
        	
            try {
            	inputStream = url.openStream();
            	bufferReader = new BufferedReader(new InputStreamReader(inputStream));
            	
            	// Leggo tutto il file di risposta alla richiesta
                while ((line = bufferReader.readLine()) != null) {
                    file += line + "\n";
                }
            } catch (IOException io_exception) {
            	io_exception.printStackTrace();
            } 
            
	        try {
	            if (inputStream != null) inputStream.close();
	        } catch (IOException io_exception) {
	            io_exception.printStackTrace();
	        }
        }
        catch (Exception exception) {
            exception.printStackTrace();
        }
        
        // Parser del file per ottenere il nome e cognome dei cittadini
        file = file.replace(",", " ");
        if (file.length() <= 5) {
        	String [] finale = {indirizzo};
        	return finale;
        }
        file = file.substring(0, file.length() - 1);
        String[] cittadini = file.split(";");
        String [] finale = new String[cittadini.length + 1];
        
        // Indirizzo nella posizione 0
        finale[0] = indirizzo;
        for (int i = 0; i < cittadini.length; i++) {
        	finale[i + 1] = cittadini[i];
        }
        return finale;        
    }
	
	
	private static String ricavaIndirizzo(Coordinate point) {
		// Converto latitudine e longitudine nel sistema WGS84 da Gauss-Boaga fuso ovest
		String GBfo = "EPSG:3003";
	    String WGS84 = "EPSG:4326";
	    
	    CoordinateTransformFactory ctFactory = new CoordinateTransformFactory();
	    CRSFactory csFactory = new CRSFactory();

	    CoordinateReferenceSystem coordinateGBfo = csFactory.createFromName(GBfo);
	    CoordinateReferenceSystem coordinateWGS84 = csFactory.createFromName(WGS84);

	    CoordinateTransform converter = ctFactory.createTransform(coordinateGBfo, coordinateWGS84);
	 
	    ProjCoordinate pointStart = new ProjCoordinate();
	    ProjCoordinate pointFin = new ProjCoordinate();
	    pointStart.x = point.x;
	    pointStart.y = point.y;

	    converter.transform(pointStart, pointFin);
	    double longitudine = (double) pointFin.x;
	    double latitudine = (double) pointFin.y;
		
		// Creo una connessione al servizio OpenStreet Map
		InputStream inputStream = null;
        BufferedReader bufferReader = null;
        String line = "";
		String file = "";
		try {
			// Definisco l'indirizzo a cui effettuare la richiesta di reverse geo decoding
            URL url = new URL("https://nominatim.openstreetmap.org/reverse?format=json&lat=" + latitudine + "&lon=" + longitudine + "&zoom=18&addressdetails=1");
            try {
            	inputStream = url.openStream();
            	bufferReader = new BufferedReader(new InputStreamReader(inputStream));
            	
            	// Leggo tutto il file di risposta alla richiesta
                while ((line = bufferReader.readLine()) != null) {
                    file += line + "\n";
                }
            } catch (IOException io_exception) {
            	io_exception.printStackTrace();
            } 
            
	        try {
	            if (inputStream != null) inputStream.close();
	        } catch (IOException io_exception) {
	            io_exception.printStackTrace();
	        }
        }
        catch (Exception exception) {
            exception.printStackTrace();
        }
		JSONParser parser = new JSONParser();
        String indirizzo = "";
        try {
        
            Object object = parser.parse(file);
            JSONObject mainObject = (JSONObject)object;
            indirizzo = mainObject.get("display_name").toString();
        } catch(Exception pe) {
            pe.printStackTrace();
        }
        return indirizzo;
	}	
}
