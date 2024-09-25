import java.sql.*; 
import java.util.Scanner;
import java.io.Console;

public class JDBCLab { 
 Connection conn = null; 
 Statement stmt = null; 
 ResultSet rs = null; 
  
 public int connectDB(){   
  try { 
    conn = DriverManager.getConnection( 
                       "jdbc:mysql://classdb.it.mtu.edu/mjpeluka",  
                       "mjpeluka",  
                       "XXX"); 
   System.out.println("Connected to the database!"); 
  } catch (SQLException e) { 
   System.out.println(e.getMessage()); 
   e.printStackTrace(); 
   return 1; 
  }   
  return 0; 
 } 
 public int newConnectDB(){
    try {
        String username=null;
        char[] password=null;
        Console console = System.console();
        if (console == null) {
            System.out.println("console is null. Run the program in terminal");
            return 1;
        }
        username = console.readLine("Please enter your name:");
        password = console.readPassword("Please enter your password:");
        conn = DriverManager.getConnection( "jdbc:mysql://classdb.it.mtu.edu/"+
            username, username, String.valueOf(password));
        System.out.println("Connected the the database!");
    } catch (SQLException e) {
        System.out.println(e.getMessage());
        e.printStackTrace();
        return 1;
    }
    return 0;
 }
 public void disconnect(){ 
  try { 
   conn.close(); 
              System.out.println("Disconnected from the database!"); 
 
  } 
  catch (SQLException ex){ 
   System.out.println("SQLException: " +  
                                      ex.getMessage()); 
   System.out.println("SQLState: " + ex.getSQLState()); 
   System.out.println("VendorError: " +  
                                      ex.getErrorCode()); 
  } 
 } 

 public void displayAccount(){
    try {
        stmt = conn.createStatement();
        rs = stmt.executeQuery("SELECT account_number,balance FROM Lab3_account");
        while (rs.next() ) {
            System.out.println(rs.getString(1)+ ","+
            rs.getString(2));
        }
    }
    catch (SQLException ex){
        System.out.println("SQLException: " + ex.getMessage());
        System.out.println("SQLState: " + ex.getSQLState());
        System.out.println("VendorError: " + ex.getErrorCode());
    }
 }

 public int transfer(String from_account_number, String to_account_number, double amount)
  throws SQLException{
    Statement stmt = null;
    ResultSet rs = null;
    int rowcount;

    try {
    // start transaction
        conn.setAutoCommit(false);
        conn.setTransactionIsolation(
        Connection.TRANSACTION_SERIALIZABLE);
    } catch (SQLException e) {
        e.printStackTrace();
        return 0;
    }

    try {
    //add code here later to check the balance of from_account_number
    //if it is smaller than amount, rollback the transaction.
        String sqlstr;
        stmt = conn.createStatement();
        sqlstr = "update Lab3_account set balance = balance - " + amount
        + " where account_number = '" + from_account_number + "'";

        rowcount = stmt.executeUpdate(sqlstr);
        System.out.println("deduct money from account "+ from_account_number + ": " +
        rowcount + " rows has been updated");

        sqlstr = "update Lab3_account set balance = balance + " + amount
        + " where account_number = '" + to_account_number + "'";

        rowcount = stmt.executeUpdate(sqlstr);
        System.out.println("save money to account "+ to_account_number + ": " +
        rowcount + " rows has been updated");
        
        conn.commit();
    }
    catch(SQLException ex){
        // handle any errors
        System.out.println("SQLException: " + ex.getMessage());
        System.out.println("SQLState: " + ex.getSQLState());
        System.out.println("VendorError: " + ex.getErrorCode());
        conn.rollback();
    }

    return 1;
 } 

 public int transfer_NoSQLInjection(String from_account_number, String to_account_number,
 double amount){
    PreparedStatement stmt = null;
    ResultSet rs = null;
    int rowcount;

    try {
        // start transaction
        conn.setAutoCommit(false);
        conn.setTransactionIsolation(
        conn.TRANSACTION_SERIALIZABLE);
    } catch (SQLException e) {
        e.printStackTrace();
        return 0;
    }
    
    try {
        String sqlstr;
        sqlstr = "select balance from Lab3_account where account_number = ? ";
        stmt = conn.prepareStatement(sqlstr);
        stmt.setString(1, from_account_number);
        rs = stmt.executeQuery();
        if (rs.next()) {
            double balance = rs.getDouble(1);
            if (balance < amount) {
            System.out.println("Insufficient balance");
            conn.rollback();
            return 0;
            }
        } else {
            System.out.println("Account does not exist ");
            conn.rollback();
            return 0;
        }
        sqlstr = "update Lab3_account set balance = balance - ? where account_number = ? ";
        stmt = conn.prepareStatement(sqlstr);
        stmt.setDouble(1, amount);
        stmt.setString(2, from_account_number);

        rowcount = stmt.executeUpdate();
        //PART 7 This is where I check if the row count updated is 1 and rollsback if not
        if (rowcount == 1){
            System.out.println("save money to account "+ to_account_number + ": " + rowcount +
            " rows has been updated");
            conn.commit();
        }else{
            System.out.println("sorry, the number of rows updated ins't 1, reverting changes");
            conn.rollback();
            return 0;
        }
        System.out.println("deduct money from account "+ from_account_number + ": " +
        rowcount + " rows has been updated");
        sqlstr = "update Lab3_account set balance = balance + ? where account_number = ? " ;
        stmt = conn.prepareStatement(sqlstr);
        stmt.setDouble(1, amount);
        stmt.setString(2, to_account_number);
        rowcount = stmt.executeUpdate();
        //PART 7 This is where I check if the row count updated is 1 and rollsback if not
        if (rowcount == 1){
            System.out.println("save money to account "+ to_account_number + ": " + rowcount +
            " rows has been updated");
            conn.commit();
        }else{
            System.out.println("sorry, the number of rows updated isn't 1, reverting changes");
            conn.rollback();
            return 0;
        }
    }
    catch (SQLException ex){
        // handle any errors
        System.out.println("SQLException: " + ex.getMessage());
        System.out.println("SQLState: " + ex.getSQLState());
        System.out.println("VendorError: " + ex.getErrorCode());
    }
    return 1;
}
 public static void main(String args[]) throws SQLException{
    
    JDBCLab dblab = new JDBCLab();
    dblab.newConnectDB();
    dblab.displayAccount();
    String from_account, to_account;
    Double balance;
    Scanner input = new Scanner(System.in);
    System.out.println("Enter the account to transfer the money from:");
    from_account = input.nextLine();
    System.out.println("Enter the account to transfer the money to:");
    to_account = input.nextLine();
    System.out.println("Enter the amount to withdraw:");
    balance = input.nextDouble();
    System.out.println("transferring $" + balance + " from " + from_account + " to " + to_account);
    dblab.transfer_NoSQLInjection(from_account, to_account, balance);

    dblab.displayAccount();
    dblab.disconnect();
 }
} 