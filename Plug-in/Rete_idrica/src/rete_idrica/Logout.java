package rete_idrica;


import java.util.Iterator;
import java.util.List;
import javax.swing.JOptionPane;
import com.vividsolutions.jump.workbench.model.Layer;
import com.vividsolutions.jump.workbench.plugin.AbstractPlugIn;
import com.vividsolutions.jump.workbench.plugin.PlugInContext;
import com.vividsolutions.jump.workbench.ui.plugin.FeatureInstaller;


public class Logout extends AbstractPlugIn {
	
	public static boolean isLogin() {
		return Login.login;
	}
	
	@Override
	public void initialize(PlugInContext context) throws Exception {
		// Inizializzazione del plug-in
		FeatureInstaller featureInstaller = new FeatureInstaller(context.getWorkbenchContext());
		featureInstaller.addMainMenuPlugin(this, new String[] {"Rete Idrica"}, "Logout", false, null, null);
	}
	
	@SuppressWarnings("rawtypes")
	@Override
	public boolean execute(PlugInContext context) throws Exception {
		
		if (!Login.login) {
			// Logout già effettuato
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "LOGIN NON EFFETTUATO");
		}
		else {
			// Chiusura della connessione del database
			DB.chiudiConnessione(context); 
	        
	        // Ottengo e chiudo tutti i Layer aperti
	        List allLayers = (List) context.getLayerManager().getLayers();
	        Iterator iterLayer = allLayers.iterator();
			while(iterLayer.hasNext()) {
				Layer myLayer = (Layer) iterLayer.next();
				context.getLayerManager().remove(myLayer);
			}
			
			// Setto la variabile di login a false
			Login.login = false;
			JOptionPane.showMessageDialog(context.getWorkbenchFrame(), "LOGOUT EFFETTUATO");
		}
		return true;
	}
}
