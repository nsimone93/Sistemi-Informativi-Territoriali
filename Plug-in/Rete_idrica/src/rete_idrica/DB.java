package rete_idrica;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import javax.swing.JOptionPane;
import com.vividsolutions.jump.workbench.plugin.PlugInContext;


public class DB {
	
	private static Connection connessione;
	private static Statement statement;
	
	public static void apriConnessione(PlugInContext context) {
		// Apertura connessione al database
		String conn_str = "jdbc:postgresql://localhost:5432/rete_idrica?user=postgres&password=postgres";
		try {
			connessione = DriverManager.getConnection(conn_str);
		} catch (SQLException sql_ex) {
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE CONNESSIONE", "ERRORE", JOptionPane.ERROR_MESSAGE);
			System.exit(0);
		}
	}
	
	public static void chiudiConnessione(PlugInContext context) {
		// Chiusura connessione al database
		try {
	        connessione.close();
	    } catch (SQLException sql_ex) {
	    	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE CHIUSURA CONNESSIONE", "ERRORE", JOptionPane.ERROR_MESSAGE);
			System.exit(0);
	    }
	}
	
	public static ResultSet eseguiQuery(String query, PlugInContext context) {
		// Esecuzione query generica
		try {
			if (connessione == null || !connessione.isValid(0))
				apriConnessione(context);
			statement = connessione.createStatement();
			ResultSet risultato = statement.executeQuery(query);
			return risultato;
		} catch (SQLException sql_ex) {
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE ESECUZIONE QUERY", "ERRORE", JOptionPane.ERROR_MESSAGE);
			return null;
		}
	}
	
}
