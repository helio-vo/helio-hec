
package hec.service_client;

import javax.xml.bind.JAXBElement;
import javax.xml.bind.annotation.XmlElementDecl;
import javax.xml.bind.annotation.XmlRegistry;
import javax.xml.namespace.QName;


/**
 * This object contains factory methods for each 
 * Java content interface and Java element interface 
 * generated in the hec.service_client package. 
 * <p>An ObjectFactory allows you to programatically 
 * construct new instances of the Java representation 
 * for XML content. The Java representation of XML 
 * content can consist of schema derived interfaces 
 * and classes representing the binding of schema 
 * type definitions, element declarations and model 
 * groups.  Factory methods for each of these are 
 * provided in this class.
 * 
 */
@XmlRegistry
public class ObjectFactory {

    private final static QName _SqlResponse_QNAME = new QName("http://service.hec/", "sqlResponse");
    private final static QName _Sql_QNAME = new QName("http://service.hec/", "sql");

    /**
     * Create a new ObjectFactory that can be used to create new instances of schema derived classes for package: hec.service_client
     * 
     */
    public ObjectFactory() {
    }

    /**
     * Create an instance of {@link Sql }
     * 
     */
    public Sql createSql() {
        return new Sql();
    }

    /**
     * Create an instance of {@link SqlResponse }
     * 
     */
    public SqlResponse createSqlResponse() {
        return new SqlResponse();
    }

    /**
     * Create an instance of {@link JAXBElement }{@code <}{@link SqlResponse }{@code >}}
     * 
     */
    @XmlElementDecl(namespace = "http://service.hec/", name = "sqlResponse")
    public JAXBElement<SqlResponse> createSqlResponse(SqlResponse value) {
        return new JAXBElement<SqlResponse>(_SqlResponse_QNAME, SqlResponse.class, null, value);
    }

    /**
     * Create an instance of {@link JAXBElement }{@code <}{@link Sql }{@code >}}
     * 
     */
    @XmlElementDecl(namespace = "http://service.hec/", name = "sql")
    public JAXBElement<Sql> createSql(Sql value) {
        return new JAXBElement<Sql>(_Sql_QNAME, Sql.class, null, value);
    }

}
