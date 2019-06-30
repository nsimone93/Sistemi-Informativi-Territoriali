package rete_idrica;


import java.sql.ResultSet;
import java.util.Iterator;
import java.util.List;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPasswordField;
import com.vividsolutions.jump.workbench.model.Layer;
import com.vividsolutions.jump.workbench.plugin.AbstractPlugIn;
import com.vividsolutions.jump.workbench.plugin.PlugInContext;
import com.vividsolutions.jump.workbench.ui.MultiInputDialog;
import com.vividsolutions.jump.workbench.ui.plugin.FeatureInstaller;


public class Login extends AbstractPlugIn {
	
	public static boolean login = false;
	
	public static boolean isLogin() {
		return login;
	}
	
	@Override
	public void initialize(PlugInContext context) throws Exception {
		// Inizializzazione del plug-in
		FeatureInstaller featureInstaller = new FeatureInstaller(context.getWorkbenchContext());
		featureInstaller.addMainMenuPlugin(this, new String[] {"Rete Idrica"}, "Login", false, null, null);
	}
	
	@SuppressWarnings("rawtypes")
	@Override
	public boolean execute(PlugInContext context) throws Exception {
		
		if (!login) {
			// Apertura della connessione del database
			DB.apriConnessione(context); 
			
			// Apertura finestra per inserimento credenziali di accesso
	        MultiInputDialog mid = new MultiInputDialog(context.getWorkbenchFrame(),"LOGIN",true);  
	        
	        // Definizione aspetto e campi finestra di dialogo        
	        mid.setBounds(700, 100, 500, 200);
	        String username = "Username: ";
	        String password = "Password: ";
	        mid.addTextField(username, "", 35, null, null);
	        
	        mid.addRow(password, new JLabel("Password: "), new JPasswordField("", 35), null, null);
	        
	        mid.setVisible(true);
	        
	        // Verifica interazione con la finestra di dialogo
	        if( !mid.wasOKPressed())                                                                     
	        {
	            DB.chiudiConnessione(context);                                                
	            return false;
	        }
	        
	        // Controllo credenziali
	        String query = "SELECT nome, cognome FROM amministratore WHERE username = '" + mid.getText(username) + "' AND password = '" + mid.getText(password) + "'";
	        ResultSet credenziali = DB.eseguiQuery(query, context);
	        if (credenziali.next()) {
	        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ACCESSO AVVENUTO CON SUCCESSO\n Benvenuto: " + credenziali.getString("nome") + " " + credenziali.getString("cognome"));
	        	login = true;
	        }
	        else {
	        	JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "CREDENZIALI NON CORRETTE", "ERRORE", JOptionPane.ERROR_MESSAGE);
	        	DB.chiudiConnessione(context);
	        	return false;
	        }
	        
	        // Ottengo e chiudo tutti i Layer aperti
	        List allLayers = (List) context.getLayerManager().getLayers();
	        Iterator iterLayer = allLayers.iterator();
			while(iterLayer.hasNext()) {
				Layer myLayer = (Layer) iterLayer.next();
				context.getLayerManager().remove(myLayer);
			}
			
			// Carimento geometrie dal database
			Util.creaLayer(context, "TRATTA");
			Util.creaLayer(context, "RU");
			Util.creaLayer(context, "POZZETTO");
		}
		else {
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "ACCESSO GIA' EFFETTUATO");
		}
		return true;
	}
}
