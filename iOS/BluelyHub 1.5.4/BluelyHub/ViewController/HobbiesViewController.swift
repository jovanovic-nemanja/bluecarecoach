//
//  HobbiesViewController.swift
//  BluelyHub
//
//  Created by Bozo Krkeljas on 1/30/21.
//

import UIKit
import SwiftyJSON

class HobbiesViewController: UIViewController, UITableViewDelegate, UITableViewDataSource {

    private var hobbies:[String] = [String]()
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

    @IBAction func onAdd(_ sender: Any) {
        let controller = UIAlertController(title: "Enter your other skill", message: nil, preferredStyle: .alert)
        controller.addTextField()
        
        let submitAction = UIAlertAction(title: "Submit", style: .default) { [unowned controller] _ in
            if let hobby = controller.textFields![0].text {
                if hobby.count > 0 {
                    self.hobbies.append(hobby)
                    self.tableView.reloadData()
                }
            }
        }
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) { (action) in
            
        }

        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    @IBAction func onSkip(_ sender: Any) {
        self.performSegue(withIdentifier: "home", sender: nil)
    }
    
    @IBAction func onDone(_ sender: Any) {
        if hobbies.count > 0 {
            var params: JSON = JSON()
            params["userid"].int = DataManager.currentUser?.id
            
            for index in 0...hobbies.count - 1 {
                params["skill\(index + 1)"].string = hobbies[index]
            }
            
            UIManager.shared.showHUD(view: self.view, title: "Saving...")

            APIManager.shared.updateSkillsAndHobbies(params, { (success, user, message) in
                UIManager.shared.hideHUD()

                if (success) {
                    DataManager.currentUser = user
                    self.performSegue(withIdentifier: "home", sender: nil)
                } else {
                    UIManager.shared.showAlert(vc: self, title: "Error", message: message!)
                    return
                }
            })
        }
    }
    
    // MARK: - UITableViewDelegate, UITableViewDataSource
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return hobbies.count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "basicStyle", for: indexPath)
        
        cell.textLabel?.text = hobbies[indexPath.row]
        
        return cell
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let controller = UIAlertController(title: "Update your other skill", message: nil, preferredStyle: .alert)
        controller.addTextField()
        controller.textFields![0].text = hobbies[indexPath.row]
        
        let submitAction = UIAlertAction(title: "Submit", style: .default) { [unowned controller] _ in
            if let hobby = controller.textFields![0].text {
                if (hobby.count > 0) {
                    self.hobbies[indexPath.row] = hobby
                    self.tableView.reloadData()
                }
            }
        }
        
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) { (action) in
            
        }
        
        controller.addAction(submitAction)
        controller.addAction(cancelAction)
        
        present(controller, animated: true)
    }
    
    func tableView(_ tableView: UITableView, commit editingStyle: UITableViewCell.EditingStyle, forRowAt indexPath: IndexPath) {
        if editingStyle == .delete {
            hobbies.remove(at: indexPath.row)
            tableView.deleteRows(at: [indexPath], with: .fade)
        } else if editingStyle == .insert {
            // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view.
        }
    }
}
