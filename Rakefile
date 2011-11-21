task :test do
    if ENV["TRAVIS"] == 'true'
        puts "Travis CI"
        system "pyrus install pecl/intl"
    end

    puts "Testing default system"
    system "phpunit"
end

task :release, :version do |t, args|
    version = args[:version]

    Rake::Task["test"]
end
