package rete_idrica;


import java.awt.Dimension;
import java.awt.Toolkit;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Iterator;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.table.DefaultTableCellRenderer;
import com.vividsolutions.jts.geom.Coordinate;
import com.vividsolutions.jts.geom.Geometry;
import com.vividsolutions.jts.io.WKTReader;
import com.vividsolutions.jump.workbench.plugin.AbstractPlugIn;
import com.vividsolutions.jump.workbench.plugin.PlugInContext;
import com.vividsolutions.jump.workbench.ui.MultiInputDialog;
import com.vividsolutions.jump.workbench.ui.plugin.FeatureInstaller;


public class Valvola extends AbstractPlugIn {
	
	private Object[] cercaRU(PlugInContext context, int id_valvola) {
		// Inizializzo le variabili necessarie
		int index = 0;
		int insert = 0;
		int num_tratte = 0;
		try {
			String query = "SELECT COUNT(*) AS num_tratte FROM tratta";
	        ResultSet risultato = DB.eseguiQuery(query, context);
	        if (risultato.next()) 
	        	num_tratte = risultato.getInt("num_tratte");
	    } catch (SQLException sql_ex) {
	    	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE ESECUZIONE QUERY", "ERRORE", JOptionPane.ERROR_MESSAGE);
	    }
		int[] tratta = new int[10 * num_tratte];
		ArrayList<Integer> ru = new ArrayList<>();
		boolean end = false;
		int id_giunzione = id_valvola;
		String query = "";
		ResultSet risultato;
		while(!end) {
			// Seleziono le tratte coinvolte dall'elemento di giunzione attuale
			try {
				// Controllo ripetizioni sull'elemento di giunzione
				if (id_giunzione != -1) {
					// Selezione tutte le tratte collegate alla giunzione attuale
					query = "SELECT id_tratta FROM iniziale WHERE id_giunzione = " + id_giunzione + "";
			        risultato = DB.eseguiQuery(query, context);
			        while(risultato.next()) {
			        	int check = risultato.getInt("id_tratta");
			        	boolean flag = false;
			        	
			        	// Controllo che le tratte non siano state già analizzate
			        	for (int i = 0; i < insert; i++) {
			        		if (tratta[i] == check) {
			        			flag = true;
			        			i = insert;
			        		}
			        	}
			        	if (flag) continue;
			        	tratta[insert] = check;
			        	insert++;
			        }
				}
		        if (index < insert) {
		        	// Ho ancora tratte da percorrere
		        	int id_tratta = tratta[index];
		        	index++;
		        	
		        	// Controllo se la tratta è collegata ad un elemento di giunzione
		        	query = "SELECT id_giunzione FROM finale WHERE id_tratta = " + id_tratta + "";
			        risultato = DB.eseguiQuery(query, context);
			        if (risultato.next()) {
			        	// La tratta è collegata ad un elemento di giunzione
			        	id_giunzione = risultato.getInt("id_giunzione");
			        }
			        else {
			        	// La tratta è collegata ad un punto RU
			        	// Seleziono il punto RU
			        	query = "SELECT id_ru FROM collega WHERE id_tratta = " + id_tratta + "";
				        risultato = DB.eseguiQuery(query, context);
				        if (risultato.next()) {
				        	ru.add(risultato.getInt("id_ru"));
				        }
				        id_giunzione = -1;
			        }
		        }
		        else {
		        	// Non ho più tratte da percorrere
		        	end = true;
		        }
			} catch (SQLException sql_ex) {
				JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE ESECUZIONE QUERY", "ERRORE", JOptionPane.ERROR_MESSAGE);
			}
		}
		
		// Devo convertire l'ArrayList ru in un array di int
		Object[] punti_ru = ru.toArray();
		return punti_ru;
	}
	
	
	@Override
	public void initialize(PlugInContext context) throws Exception {
		// Inizzializzazione plug-in
		FeatureInstaller featureInstaller = new FeatureInstaller(context.getWorkbenchContext());
		featureInstaller.addMainMenuPlugin(this, new String[] {"Rete Idrica"}, "Lista valvole", false, null, null);
	}
	
	@SuppressWarnings("rawtypes")
	@Override
	public boolean execute(PlugInContext context) throws Exception {
		// Controllo se è stato effettuato l'accesso
		if (Login.isLogin()) {
			// Cerco tutte le valvole attive
			try {
				// Cerco se ci sono valvole attive
				String query = "SELECT COUNT(*) AS numero_tot FROM el_giunzione WHERE stato = TRUE AND (tipo = 'Valvola' OR tipo = 'Valvola sorgente')";
		        ResultSet risultato = DB.eseguiQuery(query, context);
		        int num_valvole = 0;
		        if (risultato.next()) num_valvole = risultato.getInt("numero_tot");
		        risultato.close();
		        if (num_valvole == 0)
		        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "NESSUNA VALVOLA ATTIVA", "ERRORE", JOptionPane.ERROR_MESSAGE);
		        else {
		        	// Selezione tutte le valvole attive
					query = "SELECT id, id_pozzetto, data_posa FROM el_giunzione WHERE stato = TRUE AND (tipo = 'Valvola' OR tipo = 'Valvola sorgente')";
			        risultato = DB.eseguiQuery(query, context);
			        
			        // Creo una struttura per memorizzare i dati 
			        Object[][] dati = new Object[num_valvole][3];
			        int idx = 0;
			        while (risultato.next()) {
			        	dati[idx][0] = risultato.getInt("id");
			        	dati[idx][1] = risultato.getInt("id_pozzetto");
			        	dati[idx][2] = risultato.getString("data_posa");
			        	idx++;	
			        }
			        	
			        // Inizializzo le colonne della tabella 
			        String[] colonne = {"id_valvola", "id_pozzetto", "data_posa"};

			        // Creo l'oggetto tabella
			        JTable tabella_dati = new JTable(dati, colonne);
		        	
			        // Possibilità di una singola scelta
		        	tabella_dati.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
		        	
		        	// Allineamento centrale delle colonne
		        	DefaultTableCellRenderer centerRenderer = new DefaultTableCellRenderer();
		            centerRenderer.setHorizontalAlignment(JLabel.CENTER);
		            for (int i = 0; i < colonne.length; i++)
		            	tabella_dati.getColumnModel().getColumn(i).setCellRenderer(centerRenderer);
		            
		        	// Possibilità di scrorrere la lista 
		            JScrollPane scroll = new JScrollPane();
		            scroll.setViewportView(tabella_dati);
		            int id_valvola = -1;
		            boolean selezione = false;
		            
		            // Continuo finchè non viene selezionata una valvola
		            while(!selezione) {
		            	// Creazione della finestra di dialogo che mostra la lista delle valvole
		                MultiInputDialog mid = new MultiInputDialog(context.getWorkbenchFrame(),"SELEZIONA VALVOLA",true);
		                mid.add(scroll);
		                mid.setPreferredSize(new Dimension(500, 800));
		                mid.setBounds((Toolkit.getDefaultToolkit().getScreenSize().width - 500) / 2, (Toolkit.getDefaultToolkit().getScreenSize().height - 800) / 2, 0, 0);
		                mid.setVisible(true);
		                
		                // Controllo la selezione della valvola
		                if(mid.wasOKPressed()) {
		                    try {
		                    	int riga = (int) tabella_dati.getSelectedRow();
		                        id_valvola = (int) dati[riga][0];
		                        selezione = true;
		                        
		                        // Cerco tutti i punti RU coinvolti nella chiusura della valvola selezionata
		                        Object[] punti_ru = cercaRU(context, id_valvola);

		                        if(punti_ru.length == 0) 
		                        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "NESSUN EDIFICIO INTERESSATO DALLA MODIFICA DELLA VALVOLA " + id_valvola + " PRESENTE NEL POZZETTO " + (int) dati[riga][1]);
		                        else {
		                        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), punti_ru.length + " EDIFICI INTERESSATI DALLA MODIFICA DELLA VALVOLA " + id_valvola + " PRESENTE NEL POZZETTO " + (int) dati[riga][1]);
		                        	
		                        	// Creo il Layer con i raccordi utenza interessati
		                        	String nome = "RU chiusura valvola " + id_valvola;
		                        	Util.creaEdifici(context, punti_ru, nome);
		                        	WKTReader wktr = new WKTReader();
		                      
		                        	ArrayList<String> dati_tab = new ArrayList<String>();
		                        	// Ricerco tutti i cittadini interessati alla modifica della valvola
		                        	int num_righe = 0;
		                        	for (int i = 0; i < punti_ru.length; i++) {
		                        		query = "SELECT st_astext(geometry) as wkt FROM ru WHERE id = " + (int) punti_ru[i] + "";
		                	            risultato = DB.eseguiQuery(query, context);
		                	            
		                	            if (risultato.next())
		                	            {
		                	                Geometry geometry = wktr.read(risultato.getString("wkt"));
		                	                double pointX = geometry.getCoordinate().x;
		                	                double pointY = geometry.getCoordinate().y;
		                	                Coordinate point = new Coordinate(pointX, pointY);
		                	                String[] cittadini = Util.ricavaCittadini(point);
		                	                dati_tab.add("$$$");
		                	                for (int j = 0; j < cittadini.length; j++) {
		                	                	dati_tab.add(cittadini[j]);
		                	                	if (j != 0) num_righe++;
		                	                }
		                	            }
		                        	}
		                        	// Ho tutti i dati necessari all'interno di dati_tab
		                        	Object[][] cittadini_tab = new Object[num_righe][2];
		                        	Iterator iterDati = dati_tab.iterator();
		                        	int index = 0;
		                 			while(iterDati.hasNext()) {
		                 				String tmp = (String) iterDati.next();
		                 				if (tmp.equals("$$$")) {
		                 					if (iterDati.hasNext()) cittadini_tab[index][0] = iterDati.next();
		                 				}
		                 				else {
		                 					cittadini_tab[index][1] = tmp;
		                 					index++;
		                 				}
		                 			}
		                 			// Creo la tabella dei cittadini
		                 			// Inizializzo le colonne della tabella 
		        			        String[] colonne_citt = {"INDIRIZZO", "CITTADINO"};

		        			        // Creo l'oggetto tabella
		        			        JTable tabella_citt = new JTable(cittadini_tab, colonne_citt);
		        			        tabella_citt.getColumnModel().getColumn(0).setPreferredWidth(900 * 2 / 3);
		        			        tabella_citt.getColumnModel().getColumn(1).setPreferredWidth(900 / 3);
		        			        
		        			        // Possibilità di una singola scelta
		        			        tabella_citt.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
		        			        
		        		        	// Possibilità di scrorrere la lista 
		        		            JScrollPane scroll_citt = new JScrollPane();
		        		            scroll_citt.setViewportView(tabella_citt);
		        		            MultiInputDialog mid_citt = new MultiInputDialog(context.getWorkbenchFrame(),"LISTA CITTADINI",true);
		        		            mid_citt.add(scroll_citt);
		        		            mid_citt.setPreferredSize(new Dimension(900, 800));
		        		            mid_citt.setBounds((Toolkit.getDefaultToolkit().getScreenSize().width - 900) / 2, (Toolkit.getDefaultToolkit().getScreenSize().height - 800) / 2, 0, 0);
		        		            mid_citt.setVisible(true);
		                        }
		                    } catch(NullPointerException null_ex) {
		                    	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE SELEZIONE EDIFICI");
		                    }
		                }
		                else
		                    selezione = true;
		            }
		            DB.chiudiConnessione(context); 
		        }
			} catch (SQLException sql_ex) {
				JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ERRORE CONNESSIONE", "ERRORE", JOptionPane.ERROR_MESSAGE);
			}
		}
		else {
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "LOGIN NON EFFETTUATO", "ERRORE", JOptionPane.ERROR_MESSAGE);
		}
		return true;
	}
}
