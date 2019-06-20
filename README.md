## my_ci
Codeigniter 3x sürümü üzerinde özelleştirilmiş ve bazı yeni yaklaşımlar uygulanmış versiyon.

##### Demo Uygulama: 
Yapılacaklar listesi uygulaması.

##### Admin Panel:
url: admin/login
<br> 
username: admin
<br>
pw: 1

##### Veritabanı:
my_ci.sql dosyasını oluşturacağınız veritabanını içine import ediniz.

##### Neler var?:
1. View katmanı için twig engine entegre edildi.
2. Katmanlı mimari olarak **Service** katmanı kullanıldı ve **business logic** dediğimiz iş mantığı buraya taşındı.
3. Veritabanı ile haberleşme **Service** katmanı üzerinden yapıldı.
4. **Service** katmanı ve **Model** katmanı için **Abstract** sınıflar oluşturuldu ve tekrar eden ortak operasyonlar tek bir yerde yazılarak bu işlemlerin tekrar edilmemesi sağlandı.

##### Service ve Model Katmanı
Kullanıcı **id"** değeri **1** olan kaydı çekmek istediğimizde aşağıdaki işlemi yapmamız yeterli olacaktır.
1. Öncelikle User adında service ve model sınıflarını oluşturuyoruz ve bunları **Abstract** sınıflardan türetiyoruz.
2. Daha sonra **Controller** sınıfı içerisinde $this->user_service->find(1) çağırımını kullandığımızda bu işlemi başarılı bir şekilde gerçekleştirmiş oluyoruz.

Farkettiyseniz burada oluşturulan **Service** yada **Model** katmanında her hangi bir geliştirme yapmadık. Çünkü ortak olan kısımlar **Abstract** sınıflarda zaten yazıldığı için doğrudan kullanabildik.

##### Abstract Sınıflarda Ortak Olan Metodlar:

1. **insert:** ekleme
2. **update:** güncelleme
3. **delete:** silme
4. **find:** tek satırlık kayıt getirme
5. **findAll:** verilan kısıta göre tüm kaydı getirme
6. **findOneBy:** verilan kısıta göre tek satır getirme

Model katmanında hiç bir sorgu yazmadan **Abstract** sınıflarda ortak olarak yazılmış bu hazır fonksiyonları çağırarak işlemlerinizi yapabilirsiniz. Böylelikle ortak işlemleri her **Model** katmanında tekrar tekrar yazmaktan kurtulmuş olursunuz.

##### Neden Service Katmanı Var?:
**business logic** dediğimiz iş katmanı **Controller** sınıfların içinde değil **Service** katmanının içinde yazılarak bu iş mantığı yani kodumuzun asıl işlemleri yaptığı kısmın tekrar kullanılabilirliği sağlanmıştır.

**Örneğin:** Projemizde ürün arama bölümü olsun ve bunu **Controller** sınıfı içerisinde doğrudan yazdığımızı varsayalım. Yarın web servis geliştirme ihtiyacı doğduğunda **Controller** sınıfını tekrar üretip kullanamayacağımız için bu iş mantığımızı 
tekrar kodlamamız yada kopyalamamız gerekecekti. 

Burada iş mantığını ara bir katmana taşımış olmak bize her yerde bu kodu kullanabilme imkanı sağladı. Bu sayede servis katmanından ürün arama  metodunu gerekli parametrelerini vererek hem 
**Controller** içinde hemde **Api** katmanında çağırarak bu kodun tekrar kullanılabilirliğini sağlamış olduk.
